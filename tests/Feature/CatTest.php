<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use App\Models\Cat;

class CatTest extends TestCase
{
    protected $token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9sb2NhbGhvc3Q6NzAwMFwvYXBpXC9sb2dpbiIsImlhdCI6MTYzMzcwNTExOSwiZXhwIjoxNjY0ODA5MTE5LCJuYmYiOjE2MzM3MDUxMTksImp0aSI6ImVYVG9KNDBTOTdXSnppc2giLCJzdWIiOjEsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.RMvu2_T-_jqpVJOvR9LpkLj97Pz-3yRT4gCm7cidNDM';
    protected $name = 'Gatinho Teste';
    protected $uriApiCats = '/api/cats';
    protected $uriEditCat = 'api/edit-cat';
    protected $description = 'Alguma descrição de teste';

    /**
     * Adicionar gatos
     *
     * @return void
     */
    public function testAdicionarGatosBack()
    {
        $cat = [
            'name' => $this->name,
            'description' => $this->description,
            'image' => 'https://imagem-teste3.jpg'
        ];

        $response = $this->post('api/add-cat', $cat);

        $response->assertStatus(201);

    }

    public function testAdicionarGatoJaExistenteBack()
    {
        $cat = [
            'name' => $this->name,
            'description' => $this->description,
            'image' => 'https://imagem-teste4.jpg'
        ];

        $response = $this->post('api/add-cat', $cat);

        $response->assertStatus(202);

    }

    /**
     * Listar gatos da Api oficial
     *
     * @return void
     */
    public function testCheckListaDeGatosApiOficial()
    {
        $response = $this->get('api/cats-api');

        $response->assertStatus(200);
    }

    /**
     * Listar gatos sem o token
     *
     * @return void
     */
    public function testBackCheckListaDeGatosSemToken()
    {
        $response = $this->get($this->uriApiCats);

        $response->assertStatus(401);
    }

    /**
     * Listar gatos com token válido
     *
     * @return void
     */
    public function testBackCheckListaDeGatosComTokenValido()
    {
        $credentials = [
            'Authorization' => 'Bearer ' . $this->token
        ];
        $response = $this->get($this->uriApiCats, $credentials);

        $response->assertStatus(200);
    }

    /**
     * Listar gatos com token inválido
     *
     * @return void
     */
    public function testBackCheckListaDeGatosComTokenInvalido()
    {
        $credentials = [
            'Authorization' => 'Bearer ' . $this->token . 's45'
        ];
        $response = $this->get($this->uriApiCats, $credentials);

        $response->assertStatus(401);
    }

    /**
     * Listar gatos com token expirado
     *
     * @return void
     */
    public function testBackCheckListaDeGatosComTokenExpirado()
    {
        $credentials = [
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9sb2NhbGhvc3Q6NzAwMFwvYXBpXC9sb2dpbiIsImlhdCI6MTYzMzUyNzczNSwiZXhwIjoxNjMzNTMxMzM1LCJuYmYiOjE2MzM1Mjc3MzUsImp0aSI6IkE4N01QdWhMd2RjM2tiYUUiLCJzdWIiOjEsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.O9WqadpGCfJfl5ynfa1vvUZmdHt2O2qiP4ktkNwmrSY'
        ];
        $response = $this->get($this->uriApiCats, $credentials);

        $response->assertStatus(401);
    }

    /**
     * Busca Gato e adiciona quando existe na API oficial
     */
    public function testBackBuscaGato()
    {
        $creds = ['search-cat' => 'america'];
        $response = $this->post($this->uriApiCats, $creds);
        $response->assertStatus(201);
    }

    /**
     * Busca Gato que não existe
     */
    public function testBackBuscaGatoNaoExiste()
    {
        $creds = ['search-cat' => 'americanoo'];
        $response = $this->post($this->uriApiCats, $creds);
        $response->assertStatus(404);
    }

    /**
     * Buscar gato com campo vazio
     */
    public function testBackBuscaGatoVazio()
    {
        $creds = ['search-cat' => ''];
        $response = $this->post($this->uriApiCats, $creds);
        $response->assertStatus(204);
    }

    public function testBackEditarGatoSemAlteracao(){
        $gato = [
            'id_cat' => '1',
            'name' => $this->name,
            'description' => $this->description
        ];

        $response = $this->put($this->uriEditCat, $gato);
        $response->assertStatus(202);

    }

    public function testBackEditarGatoExistente(){
        $gato = [
            'id_cat' => '1',
            'name' => $this->name,
            'description' => 'Alguma descrição de teste editado 1'
        ];

        $response = $this->put($this->uriEditCat, $gato);
        $response->assertStatus(200);

    }

    public function testBackEditarGato(){
        $gato = [
            'id_cat' => '1',
            'name' => 'Gatinho Teste Edit',
            'description' => 'Alguma descrição de teste editado'
        ];

        $response = $this->put($this->uriEditCat, $gato);
        $response->assertStatus(200);

    }

    public function testBackEditarGatoInexistente(){
        $gato = [
            'id_cat' => '99',
            'name' => 'Gatinho Edit',
            'description' => 'Alguma descrição de gatinho teste editado'
        ];

        $response = $this->put($this->uriEditCat, $gato);
        $response->assertStatus(404);

    }

    /**
     * Deletar gato
     */
    public function testBackDeletarGatoExistente()
    {
        $response = $this->delete('api/delete-cat/1');
        $response->assertStatus(200);
    }

    /**
     * Deletar gato que não existe
     */
    public function testBackDeletarGatoNaoExistente()
    {
        $response = $this->delete('api/delete-cat/10');
        $response->assertStatus(404);
    }

    /**
     * Limpar lista completa
     */
    public function testBackLimparListaCompleta(){
        $response = $this->delete('api/delete-all');
        $response->assertStatus(200);
    }
}
