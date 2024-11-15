<?php

declare(strict_types=1);

namespace NotchPay\Exceptions;

final class ApiException extends NotchPayException
{
    public array $errors = [];

    public function __construct($message, array $errors = [])
    {
        parent::__construct($message);

        $this->errors = $errors;
    }
}