<?php

declare(strict_types=1);

namespace App\Tests\Year2023\Day3;

use App\FileReader\Reader;
use App\Year2023\Day3\{EngineParser, EngineSchematicCalculator};
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
        $filename = __DIR__.'/../../../src/Year2023/Day3/dataset.txt';
        $calculator = new EngineSchematicCalculator(new EngineParser(Reader::toArray($filename)));

        // Act
        $result = $calculator->compute();

        // Assert
        $this->assertEquals(526404, $result);
    }

    public function testNonRegressionSafetyPart2(): void
    {
        // Arrange
        $filename = __DIR__.'/../../../src/Year2023/Day3/dataset.txt';
        $calculator = new EngineSchematicCalculator(new EngineParser(Reader::toArray($filename)));

        // Act
        $result = $calculator->computeGearParts();

        // Assert
        $this->assertEquals(84399773, $result);
    }
}
