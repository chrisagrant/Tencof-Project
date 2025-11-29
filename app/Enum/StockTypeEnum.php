<?php

namespace App\Enum;

enum StockTypeEnum: string
{
    case IN = 'in';
    case OUT = 'out';

    public function getLabel(): string
    {
        return match ($this) {
            self::IN => 'in',
            self::OUT => 'out',
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
