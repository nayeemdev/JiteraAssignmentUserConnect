<?php

namespace App\Services;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegistrationRequest;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class AuthService
{
    use ApiResponse;

    /**
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        if (!$token = auth()->attempt($credentials)) {
            return $this->error('Credentials does not match!',Response::HTTP_UNAUTHORIZED);
        }

        return $this->successWithToken($token);
    }

    /**
     * @param RegistrationRequest $request
     * @return JsonResponse
     */
    public function register(RegistrationRequest $request): JsonResponse
    {
        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'phone' => $request->phone,
            'website' => $request->website,
        ]);

        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        if (!$user || !$token = auth()->attempt($credentials)) {
            return $this->error('Something went wrong!');
        }

        return $this->successWithToken($token);
    }

    /**
     * Generate refresh token.
     *
     * @return JsonResponse
     */
    public function refreshToken(): JsonResponse
    {
        try {
            $token = auth()->refresh();

            return $this->successWithToken($token);
        } catch (\Throwable $th) {
            return $this->error('Something went wrong!');
        }
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        try {
            auth()->logout();

            return $this->successWithMessage('Successfully logged out');
        } catch (\Throwable $th) {
            return $this->error('Something went wrong!');
        }
    }
}
