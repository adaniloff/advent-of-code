<?php

declare(strict_types=1);

namespace App\Year2023\Day7;

final readonly class HandBid
{
    public function __construct(public Hand $hand, public int $bid) {}
}
