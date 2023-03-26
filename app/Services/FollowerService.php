<?php

namespace App\Services;

use App\Http\Requests\FollowRequest;
use App\Http\Requests\UnFollowRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FollowerService
{
    use ApiResponse;

    public function follow(FollowRequest $request, $user): JsonResponse
    {
        $user = User::findOrFail($user);

        $user->followers()->attach($request->user()->id);

        return $this->successWithMessage('You are now following ' . $user->name);
    }

    public function unfollow(UnFollowRequest $request, $user): JsonResponse
    {
        $user = User::findOrFail($user);
        $user->followers()->detach($request->user()->id);

        return $this->successWithMessage('You are no longer following ' . $user->name);
    }

    public function followers(Request $request): JsonResponse
    {
        $user = auth()->user();

        $followers = $user->followers()
            ->select('users.*')
            ->when($request->has('name'), function ($query) use ($request) {
                $query->where('users.name', 'LIKE', '%'.$request->input('name').'%');
            })
            ->get();

        $followerCollection = UserResource::collection($followers->load(['address', 'company']))->toArray(request());
        return $this->success($followerCollection);
    }
}
