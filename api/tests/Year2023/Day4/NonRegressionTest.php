<?php

declare(strict_types=1);

namespace App\Tests\Year2023\Day4;

use App\FileReader\Reader;
use App\Year2023\Day3\{EngineParser, EngineSchematicCalculator};
use App\Year2023\Day4\BingoCalculator;
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
        $filename = __DIR__.'/../../../src/Year2023/Day4/dataset.txt';
        $calculator = new BingoCalculator(Reader::toArray($filename));

        // Act
        $result = $calculator->compute();

        // Assert
        $this->assertEquals(22674, $result);
    }

    public function testNonRegressionSafetyPart2(): void
    {
        // Arrange
        $filename = __DIR__.'/../../../src/Year2023/Day4/dataset.txt';
        $calculator = new BingoCalculator(Reader::toArray($filename));

        // Act
        $result = $calculator->computeScratchCards();

        // Assert
        // 1012 > too low
        $this->assertEquals(5747443, $result);
    }
}
