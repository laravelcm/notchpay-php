<?php

declare(strict_types=1);

namespace NotchPay\ApiOperations;

use NotchPay\Exceptions\InvalidArgumentException;

trait All
{
    /**
     * @throws InvalidArgumentException
     */
    public static function list($params = null): array|object
    {
        self::validateParams($params);

        $url = static::classUrl();

        if (! empty($params)) {
            $url .= '?'.http_build_query($params);
        }

        return static::staticRequest('GET', $url);
    }
}
