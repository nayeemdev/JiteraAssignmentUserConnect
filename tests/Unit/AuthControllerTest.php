<?php

namespace Tests\Unit;

use App\Http\Controllers\Api\AuthController;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegistrationRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Mockery;
use PHPUnit\Framework\TestCase;

class AuthControllerTest extends TestCase
{
    private AuthService $authService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->authService = Mockery::mock(AuthService::class);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_login(): void
    {
        $request = new LoginRequest([
            'email' => 'test@test.com',
            'password' => 'testpassword',
        ]);

        $this->authService->shouldReceive('login')
            ->once()
            ->with($request)
            ->andReturn(new JsonResponse(['token' => 'access_token']));

        $controller = new AuthController($this->authService);

        $response = $controller->login($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('access_token', $response->getData()->token);
    }


    public function test_register(): void
    {
        $request = new RegistrationRequest([
            'name' => 'Test User',
            'username' => 'testuser',
            'email' => 'test@test.com',
            'password' => 'testpassword',
            'phone' => '1234567890',
            'website' => 'https://www.test.com',
        ]);

        $this->authService->shouldReceive('register')
            ->once()
            ->with($request)
            ->andReturn(new JsonResponse(['token' => 'access_token']));

        $controller = new AuthController($this->authService);

        $response = $controller->register($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('access_token', $response->getData()->token);
    }

    public function test_refreshToken(): void
    {
        $this->authService->shouldReceive('refreshToken')
            ->once()
            ->andReturn(new JsonResponse(['token' => 'testtoken']));

        $controller = new AuthController($this->authService);

        $response = $controller->refreshToken();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('testtoken', $response->getData()->token);
    }

    public function test_logout(): void
    {
        $this->authService->shouldReceive('logout')
            ->once()
            ->andReturn(new JsonResponse(['message' => 'Successfully logged out']));

        $controller = new AuthController($this->authService);

        $response = $controller->logout();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Successfully logged out', $response->getData()->message);
    }
}
