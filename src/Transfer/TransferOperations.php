<?php

namespace NotchPay\Transfer;

use NotchPay\ApiResource;
use NotchPay\DTO\Transfers\TransfersDTO;
use NotchPay\Exceptions\InvalidArgumentException;
use NotchPay\Transfer\Trait\Validation;

class TransferOperations extends ApiResource
{
    use Validation;

    private const REQUIRED_FIELDS = ['amount', 'currency', 'recipient'];

    /**
     * @throws InvalidArgumentException
     */
    public static function initialize(TransfersDTO $transfersDTO): array|object
    {
        return self::staticRequest('POST', 'transfers', $transfersDTO->toArray());
    }

    /**
     * @throws InvalidArgumentException
     */
    public static function verify(string $reference): array|object
    {
        return self::staticRequest('GET', "transfers/{$reference}");
    }

    /**
     * @throws InvalidArgumentException
     */
    public static function list(): array|object
    {
        return self::staticRequest('GET', 'transfers');
    }
}