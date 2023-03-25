<?php

namespace Tests;

use App\Models\Address;
use App\Models\Company;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Tymon\JWTAuth\Facades\JWTAuth;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, RefreshDatabase, WithFaker;

    /**
     * Set the currently logged in user for the application.
     *
     * @param Authenticatable $user
     * @param  string|null $guard
     * @return $this
     */
    public function actingAs(Authenticatable $user, $guard = null): self
    {
        $token = JWTAuth::fromUser($user);
        $this->withHeader('Authorization', "Bearer {$token}");
        parent::actingAs($user);

        return $this;
    }

    /**
     * Create a user with address and company.
     *
     * @return User
     */
    public function createUser(): User
    {
        return User::factory()
            ->has(Address::factory())
            ->has(Company::factory())
            ->create();
    }

    /**
     * Create multiple users with address and company.
     *
     * @param int $count
     * @return array
     */
    public function createUsers(int $count = 10): array
    {
        return User::factory()
            ->count($count)
            ->has(Address::factory())
            ->has(Company::factory())
            ->create()
            ->toArray();
    }

    /**
     * Get the authorization headers.
     *
     * @return array
     */
    public function getAuthHeaders(): array
    {
        $user = $this->createUser();
        $token = auth()->login($user);

        return [
            'Authorization' => 'Bearer ' . $token
        ];
    }
}
