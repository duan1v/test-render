<?php

namespace App\Enums;

/**
 * Class FieldTypes
 * @package App\Enums
 * @version 2023/10/21 0021, 17:31
 *
 */
enum FieldTypes1: string
{
    case INTEGER = 'integer';
    case STRING = 'string';
    case DATETIME = 'dateTime';
    case TEXT = 'text';

    public static function fromLength(string $cm): FieldTypes1
    {
        return match (true) {
            $cm == 'integer' => static::INTEGER,
            $cm == 'dataTime' => static::DATETIME,
            $cm == 'text' => static::TEXT,
            default => static::STRING,
        };
    }

    public static function forSelect(): array
    {
        return array_column(self::cases(), 'name', 'value');
    }
}
