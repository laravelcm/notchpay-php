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

        return static::staticRequest('POST', "recipients", $params);
    }

    public static function list()
    {
        return self::staticRequest('GET', "recipients");
    }
    
}