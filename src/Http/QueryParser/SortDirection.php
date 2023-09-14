<?php

namespace App\Http\QueryParser;

enum SortDirection: string
{
    case ASC = 'ASC';
    case DESC = 'DESC';

    public static function toArray(): array
    {
        $array = [];
        foreach (self::cases() as $case) {
            $array[] = $case->value;
        }

        return $array;
    }
}
