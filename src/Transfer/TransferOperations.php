<?php

namespace NotchPay\Transfer;

use NotchPay\ApiResource;
use NotchPay\Exceptions\InvalidArgumentException;
use NotchPay\Transfer\Trait\Validation;

class TransferOperations extends ApiResource
{
    use Validation;

    private const REQUIRED_FIELDS = ['amount', 'currency', 'recipient'];

    /**
     * @throws InvalidArgumentException
     */
    public static function initialize(array $params): array|object
    {
        self::validateRequiredParams($params, self::REQUIRED_FIELDS);
        self::validateParams($params);

        $url = static::endPointUrl('transfers');
        return self::staticRequest('POST', $url, $params);
    }

    /**
     * @throws InvalidArgumentException
     */
    public static function verify(string $reference): array|object
    {
        $url = static::endPointUrl("transfers/{$reference}");

        return self::staticRequest('GET', $url);
    }

    /**
     * @throws InvalidArgumentException
     */
    public static function list(array $filters = []): array|object
    {
        $url = static::endPointUrl('transfers');

        return self::staticRequest('GET', $url, $filters);
    }
}