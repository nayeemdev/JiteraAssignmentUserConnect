<?php

namespace Tests\Unit\Services;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegistrationRequest;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class AuthServiceTest extends TestCase
{
    private AuthService $authService;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->authService = app()->make(AuthService::class);
        $this->user = $this->createUser();
    }

    public function test_login_should_return_error_if_credentials_do_not_match(): void
    {
        $request = new LoginRequest([
            'email' => $this->user->email,
            'password' => 'invalid-password',
        ]);

        $response = $this->authService->login($request);
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
        $this->assertArrayHasKey('message', $response->getData(true));
    }

    public function test_login_should_return_success_with_token_if_credentials_match(): void
    {
        $request = new LoginRequest([
            'email' => $this->user->email,
            'password' => 'password',
        ]);

        $response = $this->authService->login($request);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertArrayHasKey('access_token', $response->getData(true));
    }

    public function test_register_should_create_new_user_and_return_success_with_token(): void
    {
        $request = new RegistrationRequest([
            'name' => $this->faker->name,
            'username' => $this->faker->userName,
            'email' => $this->faker->email,
            'password' => 'password',
            'phone' => $this->faker->phoneNumber,
            'website' => $this->faker->url,
        ]);

        $response = $this->authService->register($request);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertArrayHasKey('access_token', $response->getData(true));
    }

    public function test_refreshToken_should_return_success_with_token_if_refresh_token_is_valid(): void
    {
        $token = auth()->login($this->user);
        $this->be($this->user);

        $response = $this->authService->refreshToken();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertArrayHasKey('access_token', $response->getData(true));
    }

    public function test_refreshToken_should_returns_server_error_if_refresh_token_is_invalid(): void
    {
        $response = $this->authService->refreshToken();
        $this->assertEquals(Response::HTTP_INTERNAL_SERVER_ERROR, $response->getStatusCode());
        $this->assertArrayHasKey('message', $response->getData(true));
    }

    public function test_should_return_message_for_logout(): void
    {
        $token = auth()->login($this->user);
        $this->be($this->user);

        $response = $this->authService->logout();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertArrayHasKey('message', $response->getData(true));
    }

    public function test_should_return_server_error_for_logout_if_token_is_invalid(): void
    {
        $response = $this->authService->logout();
        $this->assertEquals(Response::HTTP_INTERNAL_SERVER_ERROR, $response->getStatusCode());
        $this->assertArrayHasKey('message', $response->getData(true));
    }
}
