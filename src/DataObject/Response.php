<?php

declare(strict_types=1);

namespace NotchPay\DataObject;

final class Response
{
    public function __construct(
        public string $status,
        public string $message,
        public int $code,
        public Transaction $transaction,
        public string $authorizationUrl,
    ) {}
}