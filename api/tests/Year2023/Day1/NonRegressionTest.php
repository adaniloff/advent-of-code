<?php

declare(strict_types=1);

namespace App\Tests\Year2023\Day1;

use App\FileReader\Reader;
use App\Year2023\Day1\Calibration\Calculator;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
final class NonRegressionTest extends TestCase
{
    public function testNonRegressionSafety(): void
    {
        // Arrange
        $filename = __DIR__.'/../../../src/Year2023/Day1/dataset.txt';

        // Act
        $result = Calculator::computeAll(Reader::toArray($filename));

        // Assert
        $this->assertEquals(54581, $result);
    }
}
