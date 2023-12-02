<?php

declare(strict_types=1);

namespace App\Year2023\Day2;

final class CubesWatcher
{
    /**
     * @var array<'blue'|'green'|'red', int>
     */
    private array $cubes = [];

    /**
     * @param array{"red"?: int, "green"?: int, "blue"?: int} $hand
     */
    public function evaluate(array $hand): self
    {
        foreach ($hand as $color => $cubeCount) {
            $this->cubes[$color] ??= 0;
            $this->cubes[$color] >= (int) $cubeCount || $this->cubes[$color] = (int) $cubeCount;
        }

        return $this;
    }

    public function max(string $color): int
    {
        return $this->cubes[$color] ?? 0;
    }

    public function sum(): int
    {
        return array_sum($this->cubes);
    }
}
