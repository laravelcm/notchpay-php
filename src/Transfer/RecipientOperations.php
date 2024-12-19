<?php

namespace NotchPay\Transfer;

use NotchPay\ApiResource;
use NotchPay\Exceptions\InvalidArgumentException;
use NotchPay\Transfer\Trait\Validation;

class RecipientOperations extends ApiResource
{
    use Validation;

    private const REQUIRED_FIELDS = ['name', 'channel', 'number', 'phone', 'email', 'country', 'description', 'reference'];

    /**
     * @throws InvalidArgumentException
     */
    public static function create(array $params): array|object
    {
        self::validateRequiredParams($params, self::REQUIRED_FIELDS);
        self::validateParams($params);

        $url = static::endPointUrl("recipients");

        return self::staticRequest('POST', $url, $params);
    }

    /**
     * @throws InvalidArgumentException
     */
    public static function get(string $reference): array|object
    {
        $url = static::endPointUrl("transfers/{$reference}");

        return self::staticRequest('GET', $url);
    }
}