<?php

namespace Tests\Feature;

use App\Models\User;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    private const LOGIN_URL = '/api/auth/login';
    private const REGISTER_URL = '/api/auth/register';
    private const REFRESH_URL = '/api/auth/refresh-token';
    private const LOGOUT_URL = '/api/auth/logout';

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = $this->createUser();
    }

    public function test_successful_user_registration(): void
    {
        $userData = [
            'name' => $this->faker->name(),
            'username' => $this->faker->userName(),
            'email' => $this->faker->safeEmail(),
            'password' => 'password',
            'password_confirmation' => 'password',
            'phone' => $this->faker->phoneNumber(),
            'website' => $this->faker->url(),
        ];

        $response = $this->postJson(self::REGISTER_URL, $userData);

        $response->assertSuccessful()
            ->assertJsonStructure([
                'access_token',
                'token_type',
                'expires_in',
            ]);
    }

    /**
     * Test a failed user registration due to missing fields.
     *
     * @return void
     */
    public function test_failed_user_registration_missing_fields(): void
    {
        $response = $this->postJson(self::REGISTER_URL, []);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors([
                'name',
                'username',
                'email',
                'password'
            ]);
    }

    public function test_failed_user_registration_invalid_email(): void
    {
        $userData = [
            'name' => $this->faker->name(),
            'username' => $this->faker->userName(),
            'email' => 'invalid-email',
            'password' => 'password',
            'phone' => $this->faker->phoneNumber(),
            'website' => $this->faker->url(),
        ];

        $response = $this->postJson(self::REGISTER_URL, $userData);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_successful_user_login(): void
    {
        $userData = [
            'email' => $this->user->email,
            'password' => 'password',
        ];

        $response = $this->postJson(self::LOGIN_URL, $userData);

        $response->assertSuccessful()
            ->assertJsonStructure([
                'access_token',
                'token_type',
                'expires_in',
            ]);
    }

    public function test_failed_user_login_invalid_credentials(): void
    {
        $userData = [
            'email' => $this->user->email,
            'password' => 'invalid-password',
        ];

        $response = $this->postJson(self::LOGIN_URL, $userData);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertJsonStructure(['message']);
    }

    public function test_failed_user_login_missing_fields(): void
    {
        $response = $this->postJson(self::LOGIN_URL, []);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['email', 'password']);
    }

    public function test_successful_token_refresh(): void
    {
        $response = $this->postJson(self::REFRESH_URL, [], $this->getAuthHeaders());

        $response->assertSuccessful()
            ->assertJsonStructure([
                'access_token',
                'token_type',
                'expires_in',
            ]);
    }

    public function test_failed_token_refresh_missing_token(): void
    {
        $response = $this->postJson(self::REFRESH_URL);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertJsonStructure(['message']);
    }

    public function test_successful_user_logout(): void
    {
        $response = $this->postJson(self::LOGOUT_URL, [], $this->getAuthHeaders());

        $response->assertSuccessful()
            ->assertJsonStructure(['message']);
    }

    public function test_failed_user_logout_missing_token(): void
    {
        $response = $this->postJson(self::LOGOUT_URL);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertJsonStructure(['message']);
    }
}
