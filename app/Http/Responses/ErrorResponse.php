<?php

namespace App\Http\Responses;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

class ErrorResponse
{
    public static function fromException(Throwable $exception): JsonResponse
    {
        $status = match (true) {
            $exception instanceof ValidationException => $exception->status,
            $exception instanceof AuthenticationException => 401,
            $exception instanceof AuthorizationException => 403,
            $exception instanceof ModelNotFoundException => 404,
            $exception instanceof HttpExceptionInterface => $exception->getStatusCode(),
            default => 500,
        };

        $errors = $exception instanceof ValidationException ? $exception->errors() : [];

        $message = match ($status) {
            400 => __('The request could not be understood.'),
            401 => __('Unauthenticated.'),
            403 => __('This action is unauthorized.'),
            404 => __('The requested resource was not found.'),
            405 => __('The request method is not supported for this resource.'),
            409 => __('The request conflicts with the current state.'),
            419 => __('The page session has expired.'),
            422 => __('The given data was invalid.'),
            429 => __('Too many requests.'),
            503 => __('The service is temporarily unavailable.'),
            default => __('An unexpected error occurred.'),
        };

        $code = match ($status) {
            400 => 'bad_request',
            401 => 'unauthenticated',
            403 => 'forbidden',
            404 => 'not_found',
            405 => 'method_not_allowed',
            409 => 'conflict',
            419 => 'session_expired',
            422 => 'validation_error',
            429 => 'too_many_requests',
            503 => 'service_unavailable',
            default => 'internal_server_error',
        };

        return response()->json([
            'success' => false,
            'code' => $code,
            'message' => $message,
            'errors' => (object) $errors,
            'status' => $status,
        ], $status, $exception instanceof HttpExceptionInterface ? $exception->getHeaders() : []);
    }
}
