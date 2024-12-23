<?php

namespace NotchPay;

use NotchPay\ApiOperations\Request;

class Recipient extends ApiResource
{
    use Request;
    
    const OBJECT_NAME = 'recipients';

    public static function create(array $params): array|object
    {
        self::validateParams($params, true);
        $url = static::endPointUrl('initialize');

        return static::staticRequest('POST', $url, $params);
    }


    public static function verify(string $reference): array|object
    {
        $url = static::endPointUrl($reference);

        return static::staticRequest('GET', $url);
    }
    
}