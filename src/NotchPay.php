<?php

declare(strict_types=1);

namespace NotchPay;

use NotchPay\Exceptions\InvalidArgumentException;

final class NotchPay
{
    /**
     * The Notch Pay API key to be used for requests.
     */
    public static string $apiKey;

    /**
     * The instance API key, settable once per new instance
     */
    private string $instanceApiKey;

    /**
     * The base URL for the Notch Pay API.
     */
    public static string $apiBase = 'https://api.notchpay.co';

    /**
     * @return string the API key used for requests
     */
    public static function getApiKey(): string
    {
        return self::$apiKey;
    }

    /**
     * Sets the API key to be used for requests.
     *
     * @throws InvalidArgumentException
     */
    public static function setApiKey(string $apiKey): void
    {
        self::validateApiKey($apiKey);

        self::$apiKey = $apiKey;
    }

    /**
     * @param string $apiKey
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    private static function validateApiKey(string $apiKey): bool
    {
        if (empty($apiKey)) {
            throw new InvalidArgumentException('Api key must be a string and cannot be empty');
        }

        if (
            !str_starts_with($apiKey, 'b.')
            && !str_starts_with($apiKey, 'sb.')
            && !str_starts_with($apiKey, 'pk.')
            && !str_starts_with($apiKey, 'pk_test.')
        ) {
            throw new InvalidArgumentException('Api key must have a valid signature.');
        }

        return true;
    }
}
