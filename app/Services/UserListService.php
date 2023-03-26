<?php

namespace App\Services;

use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class UserListService
{
    use ApiResponse;

    /**
     * Get a user list.
     *
     * @return JsonResponse
     */
    public function get(): JsonResponse
    {
        $users = User::with(['address', 'company'])->get();
        $userResource = UserResource::collection($users)->toArray(request());

        return $this->success($userResource);
    }
}
