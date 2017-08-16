<?php

namespace App\Validation\Exceptions;

use Respect\Validation\Exceptions\ValidationException;

class CodeAvailableException extends ValidationException
{
    public static $defaultTemplates = [
        self::MODE_DEFAULT => [
            self::STANDARD => '{{name}} is invalid or has been used',
        ],
        self::MODE_NEGATIVE => [
            self::STANDARD => '{{name}} does not macth negative',
        ]
    ];
}