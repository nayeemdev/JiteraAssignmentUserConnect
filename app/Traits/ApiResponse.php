<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

trait ApiResponse
{
    /**
     * Return a success response.
     *
     * @param array $data
     * @param int $code
     * @return JsonResponse
     */
    public function success(array $data = [], int $code = ResponseAlias::HTTP_OK): JsonResponse
    {
        return response()->json($data, $code);
    }

    /**
     * Return an success response with message.
     *
     * @param array $data
     * @param string $message
     * @param int $code
     * @return JsonResponse
     */
    public function successWithMessage(string $message = 'Successfully proceed!', int $code = ResponseAlias::HTTP_OK): JsonResponse
    {
        return response()->json([
            'message' => $message,
        ], $code);
    }

    /**
     * Return a success response with token.
     *
     * @param string $token
     * @return JsonResponse
     */
    protected function successWithToken(string $token): JsonResponse
    {
        $data = [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ];

        return $this->success($data);
    }

    /**
     * Return a success response without content.
     *
     * @param int $code
     * @return JsonResponse
     */
    public function successWithoutContent(int $code = ResponseAlias::HTTP_NO_CONTENT): JsonResponse
    {
        return response()->json(null, $code);
    }

    /**
     * Return an error response.
     *
     * @param string $message
     * @param int $code
     * @return JsonResponse
     */
    public function error(string $message, int $code = ResponseAlias::HTTP_INTERNAL_SERVER_ERROR): JsonResponse
    {
        return response()->json([
            'message' => $message,
        ], $code);
    }
}
