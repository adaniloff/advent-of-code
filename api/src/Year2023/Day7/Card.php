<?php

declare(strict_types=1);

namespace App\Year2023\Day7;

enum Card: int
{
    case A = 14;
    case K = 13;
    case Q = 12;
    case J = 11;
    case T = 10;
    case NINE = 9;
    case EIGHT = 8;
    case SEVEN = 7;
    case SIX = 6;
    case FIVE = 5;
    case FOUR = 4;
    case THREE = 3;
    case TWO = 2;
    case JOKER = 1;

    /**
     * @return int[]
     */
    public static function values(): array
    {
        return array_map(fn (Card $card) => $card->value, Card::cases());
    }

    /**
     * @return string[]
     */
    public static function names(): array
    {
        return array_map(fn (Card $card) => $card->name, Card::cases());
    }

    public static function validate(int|string $card, ?bool $joker = false): false|self
    {
        if ((int) $card !== self::JOKER->value && in_array((int) $card, self::values())) {
            return self::tryFrom((int) $card);
        }
        if ((string) $card === self::J->name && $joker) {
            return self::JOKER;
        }
        if (in_array((string) $card, self::names())) {
            return constant(sprintf("%s::{$card}", __CLASS__));
        }

        return false;
    }
}
