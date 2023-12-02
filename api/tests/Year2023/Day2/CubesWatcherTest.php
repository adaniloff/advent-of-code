<?php

declare(strict_types=1);

namespace App\Tests\Year2023\Day2;

use App\Year2023\Day2\CubesWatcher;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversDefaultClass \App\Year2023\Day2\CubesWatcher
 */
final class CubesWatcherTest extends TestCase
{
    public function testMissingColorWorks(): void
    {
        // Arrange
        $watcher = new CubesWatcher();

        // Act
        $emptyColor = $watcher->max('red');
        $total = $watcher->sum();

        // Assert
        $this->assertEquals(0, $emptyColor);
        $this->assertEquals(0, $total);
    }

    /**
     * @dataProvider hands
     *
     * @param array{red?: int, green?: int, blue?: int}[]        $hands
     * @param array{total: int, red: int, green: int, blue: int} $expected
     */
    public function testWatcher(array $hands, array $expected): void
    {
        // Arrange
        // Act
        $watcher = new CubesWatcher();
        foreach ($hands as $hand) {
            $watcher->evaluate($hand);
        }

        // Assert
        foreach (['red', 'green', 'blue'] as $color) {
            $this->assertEquals($expected[$color], $watcher->max($color));
        }
        $this->assertEquals($expected['total'], $watcher->sum());
    }

    /**
     * @return array<string, array{inputs: array{red?: int, green?: int, blue?: int}[], expected: array{total: int, red: int, green: int, blue: int}}>
     */
    public static function hands(): array
    {
        return [
            'Case 1' => [
                'inputs' => [
                    [
                        'blue' => 3,
                        'red' => 4,
                    ],
                    [
                        'red' => 1,
                        'green' => 2,
                        'blue' => 6,
                    ],
                    [
                        'green' => 2,
                    ],
                ],
                'expected' => [
                    'total' => 12,
                    'red' => 4,
                    'green' => 2,
                    'blue' => 6,
                ],
            ],
            'Case 2' => [
                'inputs' => [
                    [
                        'blue' => 1,
                        'green' => 2,
                    ],
                    [
                        'red' => 1,
                        'green' => 3,
                        'blue' => 4,
                    ],
                    [
                        'green' => 1,
                        'blue' => 1,
                    ],
                ],
                'expected' => [
                    'total' => 8,
                    'red' => 1,
                    'green' => 3,
                    'blue' => 4,
                ],
            ],
            'Case 3' => [
                'inputs' => [
                    [
                        'red' => 20,
                        'green' => 8,
                        'blue' => 6,
                    ],
                    [
                        'red' => 4,
                        'green' => 13,
                        'blue' => 5,
                    ],
                    [
                        'green' => 5,
                        'red' => 1,
                    ],
                ],
                'expected' => [
                    'total' => 39,
                    'red' => 20,
                    'green' => 13,
                    'blue' => 6,
                ],
            ],
            'Case 4' => [
                'inputs' => [
                    [
                        'red' => 3,
                        'green' => 1,
                        'blue' => 6,
                    ],
                    [
                        'red' => 6,
                        'green' => 3,
                    ],
                    [
                        'red' => 14,
                        'green' => 3,
                        'blue' => 15,
                    ],
                ],
                'expected' => [
                    'total' => 32,
                    'red' => 14,
                    'green' => 3,
                    'blue' => 15,
                ],
            ],
            'Case 5' => [
                'inputs' => [
                    [
                        'red' => 6,
                        'green' => 3,
                        'blue' => 1,
                    ],
                    [
                        'red' => 1,
                        'green' => 2,
                        'blue' => 2,
                    ],
                ],
                'expected' => [
                    'total' => 11,
                    'red' => 6,
                    'green' => 3,
                    'blue' => 2,
                ],
            ],
        ];
    }
}
