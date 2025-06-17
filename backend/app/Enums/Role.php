<?php

namespace App\Enums;

enum Role: int
{
    case ADMIN = 1;
    case LIBRARIAN = 2;
    case CLIENT = 3;

    public static function id(string $name): ?int
    {
        return match ($name) {
            'admin' => self::ADMIN->value,
            'manager' => self::LIBRARIAN->value,
            'client' => self::CLIENT->value,
            default => null,
        };
    }
}
