<?php

declare(strict_types=1);

namespace App\Tests\Year2023\Day7;

use App\FileReader\Reader;
use App\Year2023\Day7\WinnerChickenDinner;
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
        $filename = __DIR__.'/../../../src/Year2023/Day7/dataset.txt';
        $winner = WinnerChickenDinner::fromSchema(Reader::toArray($filename));

        // Act
        $result = $winner->evaluate();

        // Assert
        $this->assertEquals(248179786, $result);
    }

    public function testNonRegressionSafetyPart2(): void
    {
        // Arrange
        $filename = __DIR__.'/../../../src/Year2023/Day7/dataset.txt';
        $winner = WinnerChickenDinner::fromSchema(Reader::toArray($filename), true);

        // Act
        $result = $winner->evaluate();

        // Assert
        $this->assertEquals(247885995, $result);
    }
}
