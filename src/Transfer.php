<?php

namespace NotchPay;

use NotchPay\ApiOperations\Request;

class Transfer extends ApiResource
{
    use Request;

    public static function direct(array $params): array|object
    {
        self::validateParams($params, true);

        return static::staticRequest('POST', "transfers", $params);
    }

    public static function verify(string $reference): array|object
    {
        return static::staticRequest('GET', "transfers/{$reference}");
    }

    public static function list(): array|object
    {
        return self::staticRequest('GET', 'transfers');
    }
}