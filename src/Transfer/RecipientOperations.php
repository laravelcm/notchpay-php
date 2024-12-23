<?php

namespace NotchPay\Transfer;

use NotchPay\ApiOperations\Request;
use NotchPay\ApiResource;
use NotchPay\DTO\Transfers\RecipientDTO;

class RecipientOperations extends ApiResource
{
    use Request;

    public static function create(RecipientDTO $recipientDTO): array|object
    {
        return self::staticRequest('POST', 'recipients', $recipientDTO->toArray());
    }

    public static function list()
    {
        return self::staticRequest('GET', "recipients");
    }
}