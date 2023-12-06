<?php

declare(strict_types=1);

namespace App\Tests\Year2023\Day6;

use App\Year2023\Day6\{Race, RaceReader};
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversDefaultClass \App\Year2023\Day6\RaceReader
 */
final class RaceReaderTest extends TestCase
{
    public function testContent(): void
    {
        $reader = new RaceReader(__DIR__.'/fakeDataset.txt');
        $races = $reader->read();
        $this->assertEquals([
            new Race(7, 9),
            new Race(15, 40),
            new Race(30, 200),
        ], $races);
    }

    public function testSingleRaceContent(): void
    {
        $reader = new RaceReader(__DIR__.'/fakeDataset.txt');
        $race = $reader->readNiceKerning();
        $this->assertEquals(new Race(71530, 940200), $race);
    }
}
