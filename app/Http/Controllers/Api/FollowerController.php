<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\FollowRequest;
use App\Http\Requests\UnFollowRequest;
use App\Services\FollowerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FollowerController extends Controller
{
    public function __construct(
        private readonly FollowerService $followerService
    ){}

    public function follow(FollowRequest $request, $user): JsonResponse
    {
        return $this->followerService->follow($request, $user);
    }

    public function unfollow(UnFollowRequest $request, $user): JsonResponse
    {
        return $this->followerService->unfollow($request, $user);
    }

    public function followers(Request $request): JsonResponse
    {
        return $this->followerService->followers($request);
    }
}
