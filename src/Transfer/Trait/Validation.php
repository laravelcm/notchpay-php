<?php

namespace NotchPay\Transfer\Trait;

Trait Validation
{
    protected static function validateRequiredParams(array $params, array $required): void
    {
        foreach ($required as $field) {
            if (!isset($params[$field])) {
                throw new \InvalidArgumentException("The {$field} parameter is required");
            }
        }
    }
}