<?php
namespace App\Services\Payments\Exceptions;

use Exception;

class InvalidPaymentModeException extends Exception{
    public function render($request)
    {
        return response()->json(['message' => $this->getMessage()], 422);
    }
}