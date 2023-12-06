<?php

declare(strict_types=1);

namespace App\Year2023\Day6;

final readonly class RaceSimulator
{
    public const STARTING_SPEED = 0;
    public const PUSH_ACCELERATION = 1;

    public function __construct(private Race $race) {}

    public function minimumHold(): int
    {
        return $this->possibilities(false)[0];
    }

    /**
     * @return int[]
     */
    public function possibilities(bool $breakable): array
    {
        $possibilities = [];
        $holdingTime = self::STARTING_SPEED;
        do {
            $remainingTime = $this->race->time - ++$holdingTime;
            $acceleration = $this->acceleration($holdingTime);
            $isValid = $this->race->distance < $acceleration * $remainingTime;
            if ($isValid) {
                $possibilities[] = $holdingTime;
            }
        } while ($remainingTime > 0 && (false === $breakable || $isValid));

        return $possibilities;
    }

    public function maximumHold(): int
    {
        $holdingTime = $this->race->time;
        do {
            $remainingTime = $this->race->time - --$holdingTime;
            $acceleration = $this->acceleration($holdingTime);
        } while ($remainingTime > 0 && $this->race->distance > $acceleration * $remainingTime);

        return $holdingTime;
    }

    private function acceleration(int $holdingTime): int
    {
        return self::PUSH_ACCELERATION * $holdingTime;
    }
}
