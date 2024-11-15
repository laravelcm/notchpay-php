<?php

declare(strict_types=1);

namespace NotchPay\ApiOperations;

use NotchPay\Exceptions\InvalidArgumentException;

trait Update
{
    /**
     * @throws InvalidArgumentException
     */
    public static function update(string $id, array $params): array|object
    {
        self::validateParams($params, true);

        $url = static::resourceUrl($id);

        return static::staticRequest('PUT', $url, $params);
    }
}
