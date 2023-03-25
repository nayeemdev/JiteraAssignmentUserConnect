<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\UserListService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserListController extends Controller
{
    public function __invoke(UserListService $userListService): JsonResponse
    {
        return $userListService->get();
    }
}
