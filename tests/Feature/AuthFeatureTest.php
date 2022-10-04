<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthFeatureTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var User
     */
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    /**
     * @test
     * @return void
     */
    public function shouldShowSigninPage()
    {
        $response = $this->get('/auth/signin');

        $response->assertStatus(200);
    }

    /**
     * @test
     * @return boolean
     */
    public function shouldSigninUsingValidCredential()
    {
        $this->post('/auth/signin', [
            'email' => $this->user->email,
            'password' => 'secret',
        ]);

        $this->assertAuthenticatedAs($this->user);
    }

    /**
     * @test
     * @return void
     */
    public function shouldFailedToSigninUsingInvalidCredential()
    {
        $response = $this->post('/auth/signin', [
            'email' => 'johndoe@example.com',
            'password' => 'password',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    /**
     * @test
     * @return void
     */
    public function shouldSuccessLogout()
    {
        $this->actingAs($this->user)
            ->post('/auth/signout');

        $this->assertGuest();
    }

    /**
     * @test
     */
    public function shouldShowRegisterPage(): void
    {
        $response = $this->get('/auth/register');

        $response->assertOk();
    }

    /**
     * @test
     */
    public function shouldSuccessRegisterAndRedirectToHomePage(): void
    {
        $response = $this->post('/auth/register', [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => 'secret',
            'password_confirmation' => 'secret',
        ]);

        $response->assertSessionDoesntHaveErrors();
        $response->assertRedirect();

        $this->isAuthenticated();
    }

    /**
     * @test
     */
    public function shouldErrorRegisterWhenEmailAlreadyRegistered(): void
    {
        User::factory()->create([
            'email' => 'johndoe@example.com',
        ]);

        $response = $this->post('/auth/register', [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => 'secret',
            'password_confirmation' => 'secret',
        ]);

        $response->assertSessionHasErrors(['email']);
    }
}
