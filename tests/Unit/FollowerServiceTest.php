<?php

namespace Tests\Unit;

use App\Http\Requests\UnFollowRequest;
use App\Models\Address;
use App\Models\Company;
use App\Models\Follower;
use App\Models\User;
use App\Services\FollowerService;
use App\Http\Requests\FollowRequest;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class FollowerServiceTest extends TestCase
{
    private User $user;
    private FollowerService $followerService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->followerService = app()->make(FollowerService::class);
        $this->user = $this->createUser();
    }

    public function test_user_can_follow_a_user(): void
    {
        $user = User::factory()->create();
        $follower = User::factory()->create();

        $request = FollowRequest::create('/api/follow/' . $user->id, 'POST', ['user_id' => $user->id]);
        $request->setUserResolver(function () use ($follower) {
            return $follower;
        });

        $followerService = new FollowerService();
        $response = $followerService->follow($request, $user->id);

        $this->assertDatabaseHas('followers', [
            'user_id' => $user->id,
            'follower_id' => $follower->id,
        ]);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('You are now following ' . $user->name, $response->getData()->message);
    }

    public function test_user_can_unfollow_a_user(): void
    {
        $user = User::factory()->create();
        $follower = User::factory()->create();
        $follower->followers()->attach($user->id);

        $request = UnFollowRequest::create('/api/unfollow/' . $user->id, 'DELETE', ['user_id' => $user->id]);
        $request->setUserResolver(function () use ($follower) {
            return $follower;
        });

        $followerService = new FollowerService();
        $response = $followerService->unfollow($request, $user->id);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('You are no longer following ' . $user->name, $response->getData()->message);
    }

    public function test_user_can_get_followers(): void
    {
        $this->createUsersWithFollowers(2);
        $response = $this->getJson('/api/user/followers');

        $response->assertStatus(Response::HTTP_OK);
        $this->assertCount(1, $response->getData());
    }

    public function createUsersWithFollowers(int $count): void
    {
        // create users
        $users = User::factory()
            ->count($count)
            ->has(Address::factory())
            ->has(Company::factory())
            ->create();

        $this->actingAs($users->first());

        // create followers
        $users->each(function ($user) use ($users) {
            $users->each(function ($user2) use ($user) {
                if ($user->id !== $user2->id) {
                    Follower::create([
                        'user_id' => $user->id,
                        'follower_id' => $user2->id,
                    ]);
                }
            });
        });
    }
}
