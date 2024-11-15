<?php

declare(strict_types=1);

namespace NotchPay;

final class Currency extends ApiResource
{
    const OBJECT_NAME = 'currencies';

    use ApiOperations\All;
}
