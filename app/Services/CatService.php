<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Repositories\CatRepository;
use App\Services\CacheService;
use App\ResponseDTO;

class CatService
{
    protected array $listCats;
    protected array $listCatsApi;
    protected $catRepository;
    protected $cache;
    protected $responseDTO;

    public function __construct(){
        $this->catRepository = new CatRepository;
        $this->cache = new CacheService;
        $this->responseDTO = new ResponseDTO;
    }

    private function getAllCats() : array
    {
        $listCats_ = $this->catRepository->getAllCats();
        $this->responseDTO->setResponse(true, $listCats_, null, 200);
        return $this->responseDTO->getResponse();
    }

    /**
     * Método que faz a requisição na API da TheCatApi
     */
    public function getCatsApi() : array
    {
        $listApi = Http::withHeaders([
            'x-api-key' => 'af62f5c4-c328-4929-a743-c8197d01c3ec'
        ])->get('https://api.thecatapi.com/v1/breeds');

        $cats = json_decode($listApi, true);

        foreach($cats as $key => $cat)
        {
            $this->listCatsApi[$key]['name'] = isset($cat['name'])?$cat['name']:'';
            $this->listCatsApi[$key]['description'] = isset($cat['description'])?$cat['description']:'';
            $this->listCatsApi[$key]['image'] = isset($cat['image']['url'])?$cat['image']['url']:'';
        }
        $this->responseDTO->setResponse(true, $this->listCatsApi, null, 200);
        return $this->responseDTO->getResponse();
    }

    /**
     * Método para buscar a lista dos gatos salvos localmente
     * usando a validação do token JWT
     */
    public function getCatsLocal() : array
    {
        $this->verifyCaches();
        $this->responseDTO->setResponse(true, $this->listCats, null, 200);
        return $this->responseDTO->getResponse();
    }

    /**
     * Verficar os caches
     */
    protected function verifyCaches() : void
    {
        if($this->cache::validateCache('listCatsApi') && $this->cache::validateCache('listCats'))
        {
            $this->listCatsApi = $this->cache::getCache('listCatsApi');
            $this->listCats = $this->cache::getCache('listCats');
        } else {
            $listCatsLocal_ = $this->getAllCats();
            $listCatsApi_ = $this->getCatsApi();
            $this->cache::saveCache('listCatsApi', $listCatsApi_['data']);
            $listCats_ = json_encode($listCatsLocal_['data']);
            $this->cache::saveCache('listCats', json_decode($listCats_));
            $this->verifyCaches();
        }
    }

    /**
     * Método usado para direcionamento das validações da busca
     */
    public function searchListCats(array $search) : array
    {
        $searchCatName = addslashes($search['search-cat']);

        if(!empty($searchCatName)){
            $searchCat = $this->searchCat($searchCatName);
            if($searchCat['status'])
            {
                $this->cache::deleteCache('listCats');
                setcookie('success', $searchCat['msg'], time() + 5);
                setcookie('success_list', implode('-', $searchCat['cats_added']), time() + 5);
                $this->responseDTO->setResponse(true, ['cats_added' => $searchCat['cats_added']], null, $searchCat['code']);
            } else{
                $this->responseDTO->setResponse(false, null, $searchCat['msg'], $searchCat['code']);
            }
        } else {
            $this->responseDTO->setResponse(false, null, 'Nenhum nome informado na pesquisa', 204);
        }

        return $this->responseDTO->getResponse();
    }

    /**
     * Validações da busca
     */
    protected function searchCat(string $search) : array
    {
        $this->verifyCaches();
        $listCats_ = json_encode($this->listCats);
        $listCats_ = json_decode($listCats_, true);
        $result_search = $this->hasCatSearch($search, $this->listCatsApi);
        $return = [
            'status' => false,
            'msg' => 'Todos os gatos encontrados referente á pesquisa já estão cadastrados.',
            'code' => 202
        ];

        if(count($result_search) > 0)
        {
            $count_add = 0;
            $list_cats_added = [];

            foreach($result_search as $cat)
            {
                if(!$this->validateIfHasCatCache($cat['name'], $listCats_))
                {
                    $this->catRepository->addCats($cat);
                    $list_cats_added[] = $cat['name'];
                    $count_add++;
                }
            }

            if($count_add > 0)
            {
                $return = [
                    'status' => true,
                    'msg' => 'Busca realizada e o(s) gatinho(s) encontrado(s) foi/foram adicionado(s) com sucesso!',
                    'code' => 201,  'cats_added' => $list_cats_added
                ];
            }
        } else {
            $return = [
                'status' => false,
                'msg' => 'Nenhum gato encontrado na pesquisa.',
                'code' => 404
            ];
        }

        return $return;

    }

    /**
     * Verifica na lista API Oficial salva em cache se existe o(s) gato(s) informado(s) na pesquisa,
     * se existir, retorna um array com a lista dos nomes encontrados
    */
    protected function hasCatSearch(string $search, array $listCatsApi) : array
    {
        $result_search = array();

        for($i=0; $i < count($listCatsApi); $i++)
        {
            if(mb_strpos(strtolower($listCatsApi[$i]['name']), strtolower($search)) !== false){
                $result_search[] = $listCatsApi[$i];
            }
        }
        return $result_search;
    }


    /**
     * Método de validação se já existe o gato no cache
     */
    protected function validateIfHasCatCache(string $catName, array $listCats) : bool
    {
        $i=0;
        foreach($listCats as $listCat){
            if($listCat['name'] == $catName){
                $i++;
            }
        }

        return ($i > 0) ? true : false;
    }

    // Adicionar Gato
    public function addCat(array $cat) : array
    {
        $catDB = $this->catRepository->getCatName($cat['name']);
        if($catDB){
            $this->responseDTO->setResponse(false, null, 'Este gatinho já está adicionado', 202);
        } else {
            $this->cache::deleteCache('listCats');
            $this->responseDTO->setResponse($this->catRepository->addCats($cat), null, 'Gatinho adicionado', 201);
        }

        return $this->responseDTO->getResponse();
    }

    /**
     * Validações para editar o gatinho
     */
    public function editCat(array $cat) : array
    {
        $listCatsDB = json_encode($this->getAllCats());
        $listCats_ = json_decode($listCatsDB, true);
        $cat_DB = $this->catRepository->getCatName($cat['name']);
        $status = false;
        $msg = 'Já existe um gatinho com esse nome.';
        $code = 202;

        if($this->validateIfHasCatCache($cat['name'], $listCats_['data']) && $cat_DB->id == $cat['id_cat']){
            $validateChange = $this->validateIfHasChangeEdit($cat_DB, $cat);
            $status = $validateChange['status'];
            $msg = $validateChange['msg'];
            $code = $validateChange['code'];
        } elseif(!$this->validateIfHasCatCache($cat['name'], $listCats_['data']))
        {
            $confirmEdit = $this->confirmEdit($cat);
            $status = $confirmEdit['status'];
            $msg = $confirmEdit['msg'];
            $code = $confirmEdit['code'];

        }
        $this->responseDTO->setResponse($status, null, $msg, $code);
        return $this->responseDTO->getResponse();
    }

    public function validateIfHasChangeEdit($cat_DB, $cat)
    {
        if($cat_DB->name == $cat['name'] && $cat_DB->description == $cat['description']){
            $status = false;
            $msg = 'Nenhuma alteração feita.';
            $code = 202;
        } else{
            $confirmEdit = $this->confirmEdit($cat);
            $status = $confirmEdit['status'];
            $msg = $confirmEdit['msg'];
            $code = $confirmEdit['code'];
        }

        return ['status' => $status, 'msg' => $msg, 'code' => $code];
    }

    public function confirmEdit($cat)
    {
        $this->cache::deleteCache('listCats');
        $status = $this->catRepository->updateCat($cat);
        $msg = ($status)?'Gatinho Atualizado':'Nenhum gatinho encontrado para editar';
        $code = ($status)?200:404;

        return ['status' => $status, 'msg' => $msg, 'code' => $code];
    }

    public function getCatDB(int $id_cat) : array
    {
        $catDB = $this->catRepository->getCatId($id_cat);
        $this->responseDTO->setResponse((!empty($catDB))?true:false, $catDB, null, (!empty($catDB))?200:404);
        return $this->responseDTO->getResponse();
    }

    public function deleteCat(int $id_cat) : array
    {
        if($this->catRepository->deleteCat($id_cat))
        {
            $this->cache::deleteCache('listCats');
            setcookie('success', 'Gatinho excluído com sucesso.', time() + 5, '/', "", true, true);
            $this->responseDTO->setResponse(true, null, 'Gatinho excluído', 200);
        } else {
            $this->responseDTO->setResponse(false, null, 'Nenhum gatinho encontrado para excluir', 404);
        }
        return $this->responseDTO->getResponse();
    }

    public function cleanAllCats() : array
    {
        $this->cache::deleteCache('listCats');
        setcookie('success', 'Lista de gatos excluída com sucesso.', time() + 5);
        $this->responseDTO->setResponse($this->catRepository->cleanListCats(), null, 'Lista de gatos excluída', 200);
        return $this->responseDTO->getResponse();
    }
}
