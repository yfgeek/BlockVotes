<?php

namespace App\Validation\Exceptions;

use Respect\Validation\Exceptions\ValidationException;
/**
 * CodeAvailableException
 * @author  Yifan Wu
 * @package Validation/Exceptions
 */
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