<?php

namespace Tests\Unit;

use App\Http\Resources\UsersResource;
use App\Models\User;
use App\Services\UserListService;
use Tests\TestCase;

class UserListServiceTest extends TestCase
{
    public function test_can_get_user_list(): void
    {
        $userListService = new UserListService();
        $this->createUsers();
        $users = User::with(['address', 'company'])->get();
        $response = $userListService->get();
        $expected = UsersResource::collection($users)->toArray(request());

        $this->assertCount(count($expected), $response->getData(true));
    }
}
