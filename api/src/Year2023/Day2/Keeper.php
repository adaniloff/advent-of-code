<?php

declare(strict_types=1);

namespace App\Year2023\Day2;

final readonly class Keeper
{
    /**
     * @var array{"red": int, "green": int, "blue": int}
     */
    public array $handful;

    /**
     * @param array{"red"?: int, "green"?: int, "blue"?: int}[] $hands
     */
    public function __construct(array $hands)
    {
        $this->handful = $this->findBestIn($hands);
    }

    public function hasEnoughCubes(int $maxRed, int $maxGreen, int $maxBlue): bool
    {
        return $this->handful['red'] <= $maxRed
            && $this->handful['green'] <= $maxGreen
            && $this->handful['blue'] <= $maxBlue;
    }

    /**
     * @param array{"red"?: int, "green"?: int, "blue"?: int}[] $hands
     *
     * @return array{"red": int, "green": int, "blue": int}
     */
    private function findBestIn(array $hands): array
    {
        $watcher = new CubesWatcher();
        foreach ($hands as $hand) {
            $watcher->evaluate($hand);
        }

        return [
            'red' => $watcher->max('red'),
            'green' => $watcher->max('green'),
            'blue' => $watcher->max('blue'),
        ];
    }
}
