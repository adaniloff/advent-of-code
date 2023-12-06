<?php

declare(strict_types=1);

namespace App\Tests\Year2023\Day6;

use App\Year2023\Day6\{Race, RaceReader, RaceSimulator};
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversDefaultClass \App\Year2023\Day6\RaceSimulator
 */
final class RaceSimulatorTest extends TestCase
{
    public function testMinimumHoldingButton(): void
    {
        $simulator = new RaceSimulator(new Race(7, 9));
        $this->assertEquals(2, $simulator->minimumHold());
    }

    public function testMaximumHoldingButton(): void
    {
        $simulator = new RaceSimulator(new Race(7, 9));
        $this->assertEquals(5, $simulator->maximumHold());
    }

    public function testPossibilities(): void
    {
        $simulator = new RaceSimulator(new Race(7, 9));
        $this->assertCount(4, $simulator->possibilities(false));
    }

    public function testPossibilitiesAgain(): void
    {
        $simulator = new RaceSimulator(new Race(30, 200));
        $this->assertCount(9, $simulator->possibilities(false));
    }

    public function testPossibilitiesSingleRace(): void
    {
        $simulator = new RaceSimulator(new Race(71530, 940200));
        $this->assertCount(71503, $simulator->possibilities(false));
    }
}
