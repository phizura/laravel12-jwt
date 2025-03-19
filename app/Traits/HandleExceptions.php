<?php

namespace App\Traits;

use BadMethodCallException;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenExpiredException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenInvalidException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

trait HandleExceptions
{

    public function handleException(Exception $exception)
    {
        // Handling Model Not Found
        if ($exception instanceof ModelNotFoundException) {
            $message = 'Resource not found';
            if (env('APP_ENV') !== 'production') {
                $message .= ' - ' . $exception->getMessage();
            }
            return [
                'message' => $message,
                'status' => 404
            ];
        }

        // Handling Query Exception
        if ($exception instanceof QueryException) {
            $message = 'Database query error';
            if (env('APP_ENV') !== 'production') {
                $message .= ' - ' . $exception->getMessage();
            }
            return [
                'message' => $message,
                'status' => 500
            ];
        }

        // Handling Not Found HTTP Exception
        if ($exception instanceof MethodNotAllowedHttpException) {
            $message = 'Method not allowed';
            if (env('APP_ENV') !== 'production') {
                $message .= ' - ' . $exception->getMessage();
            }
            return [
                'message' => $message,
                'status' => 404
            ];
        }

        // Handling Authentication Exception
        if ($exception instanceof AuthenticationException) {
            $message = 'Access Denied : Unauthenticated';
            if (env('APP_ENV') !== 'production') {
                $message .= ' - ' . $exception->getMessage();
            }
            return [
                'message' => $message,
                'status' => 403
            ];
        }

        // Handling Authorization Exception
        if ($exception instanceof AuthorizationException) {
            $message = 'This action is unauthorized.';
            if (env('APP_ENV') !== 'production') {
                $message .= ' - ' . $exception->getMessage();
            }
            return [
                'message' => $message,
                'status' => 403
            ];
        }


        // Handling Bad Method Call (non-existing relationship)
        if ($exception instanceof BadMethodCallException || strpos($exception->getMessage(), 'Call to undefined relationship') !== false) {
            return [
                'message' => 'Invalid relationship method or relationship does not exist.',
                'status' => 400
            ];
        }

        if ($exception instanceof BadMethodCallException || strpos($exception->getMessage(), 'Call to undefined method') !== false) {
            return [
                'message' => 'Undefined method called.',
                'status' => 400
            ];
        }

        if ($exception instanceof TokenExpiredException) {
            return [
                'message' => 'Token has expired',
                'status' => 403,
            ];
        }

        if ($exception instanceof TokenInvalidException) {
            return [
                'message' => 'Token is invalid',
                'status' => 403,
            ];
        }

        if ($exception instanceof JWTException) {
            return [
                'message' => 'Token not provided',
                'status' => 403,
            ];
        }

        // Handling Other Errors
        return [
            'message' => $exception->getMessage(),
            'status' => 500
        ];
    }
}
