<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class InvalidScopeException extends InvalidScopesException
{
    public function __construct($message = "", $code = 401, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        
        $this->message = "For this resource one of scopes is necessary";
    }
    
}
