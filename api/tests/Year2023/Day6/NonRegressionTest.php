<?php

declare(strict_types=1);

namespace App\Tests\Year2023\Day6;

use App\Year2023\Day6\{RaceReader, RaceSimulator};
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
final class NonRegressionTest extends TestCase
{
    public function testNonRegressionSafetyPart1(): void
    {
        // Arrange
        $filename = __DIR__.'/../../../src/Year2023/Day6/dataset.txt';
        $reader = new RaceReader($filename);

        // Act
        $power = 1;
        foreach ($reader->read() as $race) {
            $calculator = new RaceSimulator($race);
            $power *= count($calculator->possibilities(false));
        }

        // Assert
        $this->assertEquals(74698, $power);
    }

    public function testNonRegressionSafetyPart2(): void
    {
        $this->markTestSkipped('This test is too long to run.');

        // Arrange
        $filename = __DIR__.'/../../../src/Year2023/Day6/dataset.txt';
        $reader = new RaceReader($filename);

        // Act
        $race = $reader->readNiceKerning();
        $calculator = new RaceSimulator($race);

        // Assert
        $this->assertEquals(27563421, count($calculator->possibilities(false)));
    }
}
