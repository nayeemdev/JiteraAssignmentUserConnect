<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserListControllerTest extends TestCase
{
    private const USERS_URL = '/api/users';

    public function test_can_get_user_list(): void
    {
        $this->createUsers(50);
        $response = $this->get(self::USERS_URL);

        $response->assertStatus(200)
            ->assertJsonCount(50)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'name',
                    'username',
                    'email',
                    'address' => [
                        'street',
                        'suite',
                        'city',
                        'zipcode',
                        'geo' => [
                            'lat',
                            'lng'
                        ]
                    ],
                    'phone',
                    'website',
                    'company' => [
                        'name',
                        'catchPhrase',
                        'bs'
                    ]
                ]
            ]);
    }

    public function test_return_empty_array_if_no_user_found(): void
    {
        $response = $this->get(self::USERS_URL);

        $response->assertStatus(200)
            ->assertJsonCount(0);

        $this->assertEmpty($response->json());
    }

}
