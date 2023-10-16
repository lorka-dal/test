<?php

namespace App\Enums;

enum Family: string
{
    case Single ='Холост/не замужем';
    case Married ='Женат/замужем';
    case Divorced ='В разводе';
    case Widower ='Вдовец/вдова';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
