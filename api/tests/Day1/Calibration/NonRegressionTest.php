<?php

declare(strict_types=1);

namespace App\Tests\Day1\Calibration;

use App\Day1\Calibration\Calculator;
use PHPUnit\Framework\TestCase;
use RuntimeException;

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
        $filename = __DIR__.'/../../../src/Datasets/Day1/day1.txt';
        $file = fopen($filename, 'r');
        $size = filesize($filename);
        if (!$file || !$size) {
            throw new RuntimeException('Unable to open the file');
        }

        $content = fread($file, $size) ?: '';

        // Act
        $result = Calculator::computeAll(explode("\n", $content));

        // Assert
        $this->assertEquals(54581, $result);
    }
}
