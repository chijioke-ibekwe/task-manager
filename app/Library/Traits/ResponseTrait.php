<?php

namespace App\Library\Traits;

use Illuminate\Http\JsonResponse;

trait ResponseTrait
{
    public function response(string $message, $data = null, int $code = 200): JsonResponse
    {
        return response()->json([
            'status' => $code >= 200 && $code < 300 ? 'success' : 'failure',
            'message' => $message,
            'data' => $data
        ], $code);
    }
}