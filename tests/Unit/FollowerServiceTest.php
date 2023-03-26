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
        $follower = User::factory()->create();

        $request = FollowRequest::create('/api/follow/' . $this->user->id, 'POST', ['user_id' => $this->user->id]);
        $request->setUserResolver(function () use ($follower) {
            return $follower;
        });

        $response = $this->followerService->follow($request, $this->user->id);

        $this->assertDatabaseHas('followers', [
            'user_id' => $this->user->id,
            'follower_id' => $follower->id,
        ]);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('You are now following ' . $this->user->name, $response->getData()->message);
    }

    public function test_user_can_unfollow_a_user(): void
    {
        $follower = User::factory()->create();
        $follower->followers()->attach($this->user->id);

        $request = UnFollowRequest::create('/api/unfollow/' . $this->user->id, 'DELETE', ['user_id' => $this->user->id]);
        $request->setUserResolver(function () use ($follower) {
            return $follower;
        });

        $response = $this->followerService->unfollow($request, $this->user->id);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('You are no longer following ' . $this->user->name, $response->getData()->message);
    }

    public function test_user_can_get_followers(): void
    {
        $this->createUsersWithFollowers(2);
        $response = $this->followerService->followers(request());

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
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
