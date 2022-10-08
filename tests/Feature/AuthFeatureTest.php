<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthFeatureTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function shouldShowSigninPage(): void
    {
        $response = $this->get('/auth/signin');

        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function shouldSigninUsingValidCredential(): void
    {
        /** @var User */
        $user = User::factory()->create();

        $this->post('/auth/signin', [
            'email' => $user->email,
            'password' => 'secret',
        ]);

        $this->assertAuthenticatedAs($user);
    }

    /**
     * @test
     */
    public function shouldFailedToSigninUsingInvalidCredential(): void
    {
        $response = $this->post('/auth/signin', [
            'email' => 'johndoe@example.com',
            'password' => 'password',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    /**
     * @test
     */
    public function shouldSuccessLogout(): void
    {
        /** @var User */
        $user = User::factory()->create();

        $this->actingAs($user)->post('/auth/signout');

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
    public function shouldSuccessRegisterAndRedirectToRootPage(): void
    {
        $response = $this->post('/auth/register', [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => 'secret',
            'password_confirmation' => 'secret',
        ]);

        $response->assertSessionDoesntHaveErrors();
        $response->assertRedirect('/');
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

    /**
     * @test
     */
    public function shouldFailedToSigninWhenEmailUnverified(): void
    {
        /** @var User */
        $user = User::factory()
            ->unverified()
            ->create();

        $response = $this->post('/auth/signin', [
            'email' => $user->email,
            'password' => 'secret',
        ]);

        $response->assertSessionHasErrors(['email']);
    }
}
