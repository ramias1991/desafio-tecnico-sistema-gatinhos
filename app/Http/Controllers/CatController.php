<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CatService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class CatController extends Controller
{

    protected $catService;

    public function __construct()
    {
        $this->catService = new CatService;
    }

    // Página home dos cats
    public function index()
    {
        $user = Auth::user();
        $listCats = $this->catService->getCatsLocal();
        return view('cats', ['name' => $user->name, 'listCats' => $listCats['data']]);
    }

    // Busca dos gatos informados na pesquisa
    public function search(Request $request) :RedirectResponse
    {
        $search = $request->only(['search-cat']);
        if(!empty($search['search-cat'])){
            $searchResponse = $this->catService->searchListCats($search);
        } else {
            $searchResponse['msg'] = 'Nenhum nome informado para pesquisa!';
        }

        return redirect()->route('home')->with('warning', $searchResponse['msg']);

    }

    // Tela de edição dos gatos
    public function editCat($id_cat)
    {
        $cat = $this->catService->getCatDB($id_cat);
        if($cat['status'])
        {
            return view('edit-cat', ['cat' => $cat['data']]);
        } else {
            return redirect()->route('home')->with('warning', 'Nenhum gato encontrado para edição.');
        }

    }

    // Edição
    public function editCatAction(Request $request)
    {

        $dados = $request->only(['id_cat', 'name', 'description']);
        $catEdit = $this->catService->editCat($dados);

        if($catEdit['status'])
        {
            setcookie('success', 'Edição realizada com sucesso.', time() + 5, '/', "", true, true);
            return redirect()->route('home');
        } else {
            return back()->with('warning', $catEdit['msg']);
        }

    }

    // Deletar o gatinho
    public function deleteCat($id_cat) : RedirectResponse
    {
        if($this->catService->deleteCat($id_cat)['status'])
        {
            return redirect()->route('home');
        } else {
            return redirect()->route('home')->with('warning', 'Nenhum gato encontrado para excluir.');
        }
    }

    // Deletar todos os gatos
    public function cleanAllCats() : RedirectResponse
    {
        $this->catService->cleanAllCats();
        return redirect()->route('home');
    }

    // Trás a lista com os gatos cadastrados na base de dados / API ------ BACKEND
    public function list() : object
    {
        $listCats = $this->catService->getCatsLocal();

        return response(['status' => true, 'listCats' => $listCats['data']], $listCats['codeStatus']);

    }

    // Trás a lista com os gatos da API oficial
    public function listApi() : object
    {
        $listCats = $this->catService->getCatsApi();

        return response(['status' => true, 'listCats' => $listCats['data']], $listCats['codeStatus']);

    }

    // Busca dos gatos informados na pesquisa
    public function searchBack(Request $request) : object
    {
        $search = $request->only(['search-cat']);
        $searchResponse = $this->catService->searchListCats($search);
        $searchResponse['msg'] = ($searchResponse['status']) ? 'Busca realizada e gatinhos adicionados' : $searchResponse['msg'];
        return response($searchResponse, $searchResponse['codeStatus']);
    }

    // Adicionar Gato
    public function addCatBack(Request $request) : object
    {
        $dados = $request->only(['name', 'description', 'image']);
        $addCat = $this->catService->addCat($dados);
        return response($addCat, $addCat['codeStatus']);
    }

    // Edição
    public function editCatBack(Request $request) : object
    {

        $dados = $request->only(['id_cat', 'name', 'description']);
        $catEdit = $this->catService->editCat($dados);

        return response($catEdit, $catEdit['codeStatus']);

    }

    // Deletar o gatinho
    public function deleteCatBack($id_cat) : object
    {
        $delCat = $this->catService->deleteCat($id_cat);
        return response($delCat, $delCat['codeStatus']);
    }

    // Deletar todos os gatos
    public function cleanAllCatsBack() : object
    {
        return response($this->catService->cleanAllCats(), 200);
    }

}
