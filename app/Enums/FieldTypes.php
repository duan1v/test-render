<?php

namespace App\Enums;

/**
 * Class FieldTypes
 * @package App\Enums
 * @version 2023/10/21 0021, 17:31
 *
 */
enum FieldTypes: string
{
    case INTEGER = '->default(0)';
    case STRING = '->default("")';
    case DATETIME = '->nullable()';

    public static function fromLength(string $cm): FieldTypes
    {
        return match (true) {
            $cm == 'integer' => static::INTEGER,
            $cm == 'string' => static::STRING,
            default => static::DATETIME,
        };
    }

    public static function forSelect(): array
    {
        return array_column(self::cases(), 'name', 'value');
    }
}
