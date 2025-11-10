<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class ResponseException extends Exception
{
    protected $response;

    public function __construct(array $response)
    {
        $this->response = $response;
    }

    public function getResponse(): JsonResponse
    {
        return response()->json($this->response);
    }
}
