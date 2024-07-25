<?php

namespace App\Library\Traits;

use App\Enums\ResponseStatus;
use Illuminate\Http\JsonResponse;

trait ResponseTrait
{
    public function response(string|null $message, $data = null, int $code = 200): JsonResponse
    {
        return response()->json([
            'status' => $code >= 200 && $code < 300 ? ResponseStatus::SUCCESS : ResponseStatus::FAILURE,
            'message' => $message,
            'data' => $data
        ], $code);
    }
}