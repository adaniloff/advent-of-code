<?php

declare(strict_types=1);

namespace App\Year2023\Day2;

use App\FileReader\Reader;

final readonly class GamesManager
{
    /**
     * @param array{"red"?: int, "green"?: int, "blue"?: int}[][] $games
     */
    public function __construct(
        private array $games,
        private int $maxRed,
        private int $maxGreen,
        private int $maxBlue
    ) {}

    public function computeIds(): int
    {
        $ids = [];
        foreach ($this->games as $id => $hands) {
            $keeper = new Keeper($hands);
            if ($keeper->hasEnoughCubes(
                maxRed: $this->maxRed,
                maxGreen: $this->maxGreen,
                maxBlue: $this->maxBlue,
            )) {
                $ids[$id + 1] = ($keeper->handful['red'] ?: 1)
                    * ($keeper->handful['green'] ?: 1)
                    * ($keeper->handful['blue'] ?: 1);
            }
        }

        return array_sum(array_keys($ids));
    }

    public function computePower(): int
    {
        $sum = 0;
        foreach ($this->games as $hands) {
            $keeper = new Keeper($hands);
            $sum += $keeper->handful['red']
                * $keeper->handful['green']
                * $keeper->handful['blue'];
        }

        return $sum;
    }

    /**
     * @return array{"red"?: int, "green"?: int, "blue"?: int}[][]
     */
    public static function fileToGames(string $filename): array
    {
        $games = [];
        $lines = Reader::toArray($filename);
        foreach ($lines as $line) {
            $hands = [];
            $data = explode(':', str_replace(' ', '', $line));
            $parts = explode(';', $data[1]);
            array_map(function ($datum) use (&$hands): void {
                $rawHands = explode(',', $datum);
                $hand = [];
                foreach ($rawHands as $rawHand) {
                    preg_match('#^(\d+)(red|green|blue)$#', $rawHand, $matches);
                    $hand[$matches[2]] = (int) $matches[1];
                }
                $hands[] = $hand;
            }, $parts);
            $games[] = $hands;
        }

        return $games;
    }
}
