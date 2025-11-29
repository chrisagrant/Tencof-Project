<?php

namespace App\Enum;

enum UserRoleEnum: string
{
    case OWNER = 'owner';
    case ADMIN = 'admin';
    case KASIR = 'kasir';

    public function getLabel(): string
    {
        return match ($this) {
            self::OWNER => 'owner',
            self::ADMIN => 'admin',
            self::KASIR => 'kasir',
        };
    }

    public static function toArray(): array
    {
        $cases = [];
        foreach (self::cases() as $case) {
            $cases[] = [
                'id' => $case->value,
                'name' => $case->getLabel(),
            ];
        }
        return $cases;
    }
}
