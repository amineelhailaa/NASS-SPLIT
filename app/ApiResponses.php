<?php

namespace App;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

trait ApiResponses
{
    protected function successResponse($data, ?string $message = null, int $code = 200): JsonResponse
    {
        return response()->json([
            'status' => 'Success',
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    protected function errorResponse(?string $message, int $code, $errors = null): JsonResponse
    {
        return response()->json([
            'status' => 'Error',
            'message' => $message,
            'errors' => $errors,
        ], $code);
    }

    protected function createdResponse($data, string $message = 'Resource created successfully'): JsonResponse
    {
        return $this->successResponse($data, $message, 201);
    }

    protected function noContentResponse(): Response
    {
        //        return response()->json(null, 204);
        return response()->noContent();
    }
}
