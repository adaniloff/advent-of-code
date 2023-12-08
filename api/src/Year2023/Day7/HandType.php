<?php

declare(strict_types=1);

namespace App\Year2023\Day7;

enum HandType: int
{
    case FIVE_OF_A_KIND = 7;
    case FOUR_OF_A_KIND = 6;
    case FULL_HOUSE = 5;
    case THREE_OF_A_KIND = 4;
    case TWO_PAIR = 3;
    case SINGLE_PAIR = 2;
    case HIGH_CARD = 1;

    /**
     * @return int[]
     */
    public static function values(): array
    {
        return array_map(fn (HandType $card) => $card->value, HandType::cases());
    }

    public static function detect(Hand $hand): self
    {
        $counts = self::count($hand);
        $uniques = self::unique($hand);

        $jokers = $counts[Card::JOKER->value] ?? 0;
        unset($counts[Card::JOKER->value]);

        rsort($counts);
        $maxCount = $counts[0] + $jokers;

        return match (true) {
            5 == $maxCount => self::FIVE_OF_A_KIND,
            4 == $maxCount => self::FOUR_OF_A_KIND,
            3 == $maxCount && (2 + ($jokers ? 1 : 0)) === count($uniques) => self::FULL_HOUSE,
            3 == $maxCount => self::THREE_OF_A_KIND,
            2 == $maxCount && (3 + ($jokers ? 1 : 0)) === count($uniques) => self::TWO_PAIR,
            (4 + ($jokers ? 1 : 0)) == count($uniques) => self::SINGLE_PAIR,
            default => self::HIGH_CARD,
        };
    }

    /**
     * @return array<int, int>
     */
    private static function count(Hand $hand): array
    {
        $counts = [];
        foreach ($hand->cards as $card) {
            $counts[$card->value] ??= 0;
            ++$counts[$card->value];
        }

        return $counts;
    }

    /**
     * @return array<int, int>
     */
    private static function unique(Hand $hand): array
    {
        return array_keys(self::count($hand));
    }
}
