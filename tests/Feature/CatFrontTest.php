<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use App\Models\Cat;

class CatFrontTest extends TestCase
{
    protected $token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9sb2NhbGhvc3Q6NzAwMFwvYXBpXC9sb2dpbiIsImlhdCI6MTYzMzcwNTExOSwiZXhwIjoxNjY0ODA5MTE5LCJuYmYiOjE2MzM3MDUxMTksImp0aSI6ImVYVG9KNDBTOTdXSnppc2giLCJzdWIiOjEsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.RMvu2_T-_jqpVJOvR9LpkLj97Pz-3yRT4gCm7cidNDM';
    protected $email = 'teste@gmail.com';
    protected $senhaLogin = '123456';
    protected $name = 'Gatinho Teste';
    protected $nameEdit = 'Gatinho Teste Edit';
    protected $description = 'Alguma descrição de teste';
    protected $uriCatsFront = '/api/cats-front';
    protected $uriApiEditCatFront = 'api/edit-cat-front';

    /**
     * Acessar Home com autenticação
     *
     * @return void
     */
    public function testAcessarHomeGatosComAutenticacao()
    {
        $creds = [
            'email' => $this->email,
            'password' => $this->senhaLogin
        ];

        Auth::attempt($creds);
        $response = $this->get('/cats');

        $response->assertStatus(200);

    }

    /**
     * Acessar Home sem autenticação
     *
     * @return void
     */
    public function testAcessarHomeGatosSemAutenticacao()
    {
        $response = $this->get('/cats');

        $response->assertStatus(302);

    }

    /**
     * Criar gatos
     *
     * @return void
     */
    public function testCriarGatos()
    {
        Cat::factory()->create([
            'name' => 'Gatinho Teste 1',
            'description' => $this->description,
            'image' => 'https://imagem-teste1.jpg'
        ]);

        Cat::factory()->create([
            'name' => $this->name,
            'description' => $this->description,
            'image' => 'https://imagem-teste2.jpg'
        ]);

        $this->assertDatabaseHas('cats', ['name' => 'Gatinho Teste 1']);

    }

    /**
     * Busca Gato e adiciona quando existe na API oficial
     */
    public function testBuscaGato()
    {
        $creds = ['search-cat' => 'america'];
        $response = $this->post($this->uriCatsFront, $creds);
        $response->assertStatus(302);
    }

    /**
     * Busca Gato que não existe
     */
    public function testBuscaGatoNaoExiste()
    {
        $creds = ['search-cat' => 'americanoo'];
        $response = $this->post($this->uriCatsFront, $creds);
        $response->assertStatus(302);
    }

    /**
     * Buscar gato com campo vazio
     */
    public function testBuscaGatoVazio()
    {
        $creds = ['search-cat' => ''];
        $response = $this->post($this->uriCatsFront, $creds);
        $response->assertStatus(302);
    }

    /**
     * Tela de editar o gato existente
     */
    public function testTelaEditarGatoExistente()
    {
        $creds = [
            'email' => $this->email,
            'password' => $this->senhaLogin
        ];

        Auth::attempt($creds);
        $response = $this->get('/edit-cat/1');
        $response->assertStatus(200);
    }

    /**
     * Tela de editar o gato não existente
     */
    public function testTelaEditarGatoNaoExistente()
    {
        $creds = [
            'email' => $this->email,
            'password' => $this->senhaLogin
        ];

        Auth::attempt($creds);
        $response = $this->get('/edit-cat/99');
        $response->assertStatus(302);
    }

    public function testEditarGatoSemAlteracao(){
        $gato = [
            'id_cat' => '1',
            'name' => $this->name,
            'description' => $this->description
        ];

        $response = $this->post($this->uriApiEditCatFront, $gato);
        $response->assertStatus(302);

    }

    public function testEditarGato(){
        $gato = [
            'id_cat' => '1',
            'name' => $this->nameEdit,
            'description' => 'Alguma descrição de teste editado 3'
        ];

        $response = $this->post($this->uriApiEditCatFront, $gato);
        $response->assertStatus(302);

    }

    public function testEditarGatoExistente(){
        $gato = [
            'id_cat' => '1',
            'name' => $this->name,
            'description' => 'Alguma descrição de teste editado 4'
        ];

        $response = $this->post($this->uriApiEditCatFront, $gato);
        $response->assertStatus(302);

    }

    public function testEditarGatoInexistente(){
        $gato = [
            'id_cat' => '99',
            'name' => $this->nameEdit,
            'description' => 'Alguma descrição de teste editado 5'
        ];

        $response = $this->post($this->uriApiEditCatFront, $gato);
        $response->assertStatus(302);

    }

    /**
     * Deletar gato
     */
    public function testDeletarGatoExistente()
    {
        $response = $this->get('/delete-cat/1');
        $response->assertStatus(302);
    }

    /**
     * Deletar gato que não existe
     */
    public function testDeletarGatoNaoExistente()
    {
        $response = $this->get('/delete-cat/10');
        $response->assertStatus(302);
    }

    /**
     * Limpar lista completa
     */
    public function testLimparListaCompleta(){
        $response = $this->get('/clean-all');
        $response->assertStatus(302);
    }
}
