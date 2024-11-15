<?php

declare(strict_types=1);

namespace NotchPay;

final class Country extends ApiResource
{
    const OBJECT_NAME = 'countries';

    use ApiOperations\All;
}
