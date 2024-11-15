<?php

declare(strict_types=1);

namespace NotchPay\ApiOperations;

use NotchPay\Exceptions\InvalidArgumentException;

trait Create
{
    /**
     * @throws InvalidArgumentException
     */
    public static function create($params): array|object
    {
        self::validateParams($params, true);

        $url = static::classUrl();

        return static::staticRequest('POST', $url, $params);
    }
}
