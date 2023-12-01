<?php

declare(strict_types=1);

namespace App\Tests\Year2023\Day1;

use App\Year2023\Day1\Calibration\Calculator;
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
        $filename = __DIR__.'/../../../src/Year2023/Day1/dataset.txt';
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
