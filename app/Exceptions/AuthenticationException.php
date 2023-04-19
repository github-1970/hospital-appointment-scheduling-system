<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class AuthenticationException extends Exception
{
    public function render($request)
    {
        return new JsonResponse(['error' => __('http-statuses.401'), 'status' => 401], 401);
    }
}
