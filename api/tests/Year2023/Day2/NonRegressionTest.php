<?php

declare(strict_types=1);

namespace App\Tests\Year2023\Day2;

use App\Year2023\Day2\GamesManager;
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
        $games = GamesManager::fileToGames(__DIR__.'/../../../src/Year2023/Day2/dataset.txt');
        $manager = new GamesManager($games, maxRed: 12, maxGreen: 13, maxBlue: 14);

        // Act
        $result = $manager->computeIds();

        // Assert
        $this->assertEquals(2486, $result);
    }

    public function testNonRegressionSafetyPart2(): void
    {
        // Arrange
        $games = GamesManager::fileToGames(__DIR__.'/../../../src/Year2023/Day2/dataset.txt');
        $manager = new GamesManager($games, maxRed: 12, maxGreen: 13, maxBlue: 14);

        // Act
        $result = $manager->computePower();

        // Assert
        $this->assertEquals(87984, $result);
    }
}
