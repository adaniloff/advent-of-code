<?php

declare(strict_types=1);

namespace App\Year2023\Day6;

final readonly class Race
{
    public function __construct(public int $time, public int $distance) {}
}
