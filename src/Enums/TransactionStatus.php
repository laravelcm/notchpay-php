<?php

declare(strict_types=1);

namespace NotchPay\Enums;

enum TransactionStatus: string
{
    case Pending = 'pending';

    case Complete = 'complete';
}
