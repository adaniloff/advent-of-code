<?php

declare(strict_types=1);

namespace App\Day1\Calibration;

final class Calculator
{
    public static function first(string $input): int
    {
        preg_match('/\d/', $input, $matches);

        return (int) $matches[0];
    }

    public static function last(string $input): int
    {
        preg_match_all('/\d/', $input, $matches);

        $matches = $matches[0];

        return (int) $matches[count($matches) - 1];
    }

    public static function compute(string $input): int
    {
        return (int) (self::first($input) . '' . self::last($input));
    }

    public static function computeAll(array $inputs): int
    {
        $sum = 0;
        foreach ($inputs as $input) {
            $sum += self::compute($input);
        }

        return $sum;
    }
}
