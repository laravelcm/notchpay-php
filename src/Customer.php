<?php

declare(strict_types=1);

namespace NotchPay;

use NotchPay\Exceptions\InvalidArgumentException;

final class Customer extends ApiResource
{
    const OBJECT_NAME = 'customers';

    use ApiOperations\All;
    use ApiOperations\Create;
    use ApiOperations\Fetch;
    use ApiOperations\Update;

    /**
     * @param string $customerId containing the id of the customer to block
     *
     * @throws InvalidArgumentException
     * @link https://developer.notchpay.co/#customer-whitelist-blacklist
     */
    public static function block(string $customerId): array|object
    {
        $url = static::classUrl().'/'.$customerId.'/block';

        return static::staticRequest('PUT', $url);
    }

    /**
     * @param string $customerId containing the id of the customer to unblock
     *
     * @throws InvalidArgumentException
     * @link https://developer.notchpay.co/#customer-whitelist-blacklist
     */
    public static function unblock(string $customerId): array|object
    {
        $url = static::classUrl().'/'.$customerId.'/unblock';

        return static::staticRequest('PUT', $url);
    }

    /**
     * @param string $customerId containing the id of the customer to delete
     *
     * @throws InvalidArgumentException
     * @link https://developer.notchpay.co/#customer-whitelist-blacklist
     */
    public static function delete(string $customerId): array|object
    {
        $url = static::classUrl().'/'.$customerId;

        return static::staticRequest('delete', $url);
    }
}
