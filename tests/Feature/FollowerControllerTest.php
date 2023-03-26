<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class FollowerControllerTest extends TestCase
{
    private User $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = $this->createUser();
    }

    public function test_guest_cannot_follow_or_unfollow(): void
    {
        $response = $this->postJson("/api/user/{$this->user->id}/follow");
        $response->assertUnauthorized();

        $response = $this->postJson("/api/user/{$this->user->id}/unfollow");
        $response->assertUnauthorized();
    }

    public function test_authenticated_user_can_follow_and_unfollow(): void
    {
        $follower = $this->createUser();

        // Login the follower user
        $this->actingAs($follower);

        // Test follow
        $response = $this->postJson("/api/user/{$this->user->id}/follow");
        $response->assertSuccessful();
        $response->assertJson([
            'message' => "You are now following {$this->user->name}"
        ]);

        // Test unfollow
        $response = $this->postJson("/api/user/{$this->user->id}/unfollow");
        $response->assertSuccessful();
        $response->assertJson([
            'message' => "You are no longer following {$this->user->name}"
        ]);
    }

    public function test_follow_and_unfollow_validation(): void
    {
        $follower = $this->createUser();

        // Login the follower user
        $this->actingAs($follower);

        // Test follow with invalid data
        $response = $this->postJson("/api/user/12121212/follow", []);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(['user']);

        // Test unfollow with invalid data
        $response = $this->postJson("/api/user/121212121/unfollow", []);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(['user']);
    }

    public function test_user_can_get_followers(): void
    {
        $this->actingAs($this->user);

        // Create users with specific names to test filtering
        $follower1 = User::factory()->has(Address::factory())->has(Company::factory())->create(['name' => 'John Doe']);
        $follower2 = User::factory()->has(Address::factory())->has(Company::factory())->create(['name' => 'Jane Doe']);
        $follower3 = User::factory()->has(Address::factory())->has(Company::factory())->create(['name' => 'Jack Smith']);

        // Follow the users
        $this->user->followers()->attach($follower1->id);
        $this->user->followers()->attach($follower2->id);
        $this->user->followers()->attach($follower3->id);

        // Test filtering by name
        $response = $this->get('/api/user/followers');

        $response->assertStatus(200)
            ->assertJsonCount(3)
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

    public function test_filter_followers_by_name(): void
    {
        $this->actingAs($this->user);

        // Create users with specific names to test filtering
        $follower1 = User::factory()->has(Address::factory())->has(Company::factory())->create(['name' => 'John Doe']);
        $follower2 = User::factory()->has(Address::factory())->has(Company::factory())->create(['name' => 'Jane Doe']);
        $follower3 = User::factory()->has(Address::factory())->has(Company::factory())->create(['name' => 'Jack Smith']);

        // Follow the users
        $this->user->followers()->attach($follower1->id);
        $this->user->followers()->attach($follower2->id);
        $this->user->followers()->attach($follower3->id);

        // Test filtering by name
        $response = $this->get('/api/user/followers?name=Doe');
        $response->assertStatus(200)
            ->assertJsonCount(2)
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
}
