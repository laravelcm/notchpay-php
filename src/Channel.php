<?php

namespace NotchPay;

class Channel extends ApiResource
{
    use ApiOperations\All;
    
    public static function list(): array|object
    {
        return self::staticRequest('GET', 'channels');
    }
}