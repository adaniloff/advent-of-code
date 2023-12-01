<?php

declare(strict_types=1);

namespace App\Day1\Calibration;

final class Calculator
{
    private const MASKS = [
        'zero' => 0,
        'one' => 1,
        'two' => 2,
        'three' => 3,
        'four' => 4,
        'five' => 5,
        'six' => 6,
        'seven' => 7,
        'eight' => 8,
        'nine' => 9,
    ];

    public static function first(string $input): int
    {
        $input = self::sortConvertedLetters($input);
        preg_match('/\d/', $input, $matches);

        return (int) ($matches[0] ?? 0);
    }

    public static function last(string $input): int
    {
        $input = self::sortConvertedLetters($input, true);
        preg_match_all('/\d/', $input, $matches);

        $matches = $matches[0];

        return (int) ($matches[count($matches) - 1] ?? 0);
    }

    public static function compute(string $input): int
    {
        return (int) (self::first($input).''.self::last($input));
    }

    public static function computeAll(array $inputs): int
    {
        $sum = 0;
        foreach ($inputs as $input) {
            $sum += self::compute($input);
        }

        return $sum;
    }

    private static function sortConvertedLetters(string $input, bool $reversed = false): string
    {
        /**
         * @var array<int, string> $markers
         */
        $markers = [];
        foreach (self::MASKS as $mask => $value) {
            if ($reversed) {
                $position = strrpos($input, $mask);
            } else {
                $position = strpos($input, $mask);
            }
            if (false === $position) {
                continue;
            }

            $markers[$position] = $mask;
        }
        $reversed ? krsort($markers) : ksort($markers);
        foreach ($markers as $mask) {
            $input = str_replace($mask, (string) self::MASKS[$mask], $input);
        }

        return $input;
    }
}
