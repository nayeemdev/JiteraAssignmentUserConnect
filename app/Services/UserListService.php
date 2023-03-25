<?php

namespace App\Services;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegistrationRequest;
use App\Http\Resources\UsersResource;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

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
        $userResource = UsersResource::collection($users)->toArray(request());

        return $this->success($userResource);
    }
}
