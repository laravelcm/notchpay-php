<?php

declare(strict_types=1);

namespace NotchPay\DataObject;

final class Transaction
{
    public function __construct(
        public string $reference,
        public int $amount,
        public int $amountTotal,
        public int $convertedAmount,
        public string $sandbox,
        public string $currency,
        public string $customer,
        public string $status,
        public string $callback,
        public string $geo,
        public \DateTime $createdAt,
        public \DateTime $updatedAt,
        public int $fee = 0
    ) {}
}