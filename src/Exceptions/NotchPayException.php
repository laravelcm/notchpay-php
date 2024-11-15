<?php

declare(strict_types=1);

namespace NotchPay\Exceptions;

class NotchPayException extends \Exception
{
    public function __construct($message)
    {
        parent::__construct($message);
    }
}