<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class UserTest extends TestCase
{

    use DatabaseTransactions;

    /**
     * Teste de criação de usuário.
     *
     * @return void
     */
    public function testCreateUser()
    {
        User::factory()->create([
            'name' => 'João da Silva',
            'email' => 'joao@email.com',
            'password' => bcrypt(12345678)
        ]);

        $this->assertDatabaseHas('users',['email' => 'joao@email.com']);
    }

    /**
     * Teste de login sem email e senha.
     *
     * @return void
     */
    public function testTelaLoginUser()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    /**
     * Teste de login sem email e senha.
     *
     * @return void
     */
    public function testLoginApiUser()
    {
        $credentials = [
            'email' => 'teste@gmail.com',
            'password' => 123456
        ];

        $response = $this->post('api/login', $credentials);
        $response->assertStatus(200);
    }

    /**
     * Teste de login sem email e senha.
     *
     * @return void
     */
    public function testLoginApiUserSemCredenciais()
    {
        $credentials = [
            'email' => '',
            'password' => 123456
        ];

        $response = $this->post('/api/login', $credentials);
        $response->assertStatus(401);
    }

    /**
     * Teste de login com credenciais inválidas.
     *
     * @return void
     */
    public function testLoginApiUserCredenciaisInválidas()
    {
        $credentials = [
            'email' => 'teste@gmail.com',
            'password' => 12345689
        ];

        $response = $this->post('/api/login', $credentials);
        $response->assertStatus(401);
    }

    /**
     * Teste de logout
     *
     * @return void
     */
    public function testLogoutUser()
    {
        $response = $this->get('/logout/logout');
        $response->assertStatus(302);
    }

}
