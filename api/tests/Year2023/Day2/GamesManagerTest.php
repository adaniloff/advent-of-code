<?php

declare(strict_types=1);

namespace App\Tests\Year2023\Day2;

use App\Year2023\Day2\GamesManager;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversDefaultClass \App\Year2023\Day2\GamesManager
 */
final class GamesManagerTest extends TestCase
{
    private GamesManager $manager;

    protected function setUp(): void
    {
        $games = [];
        array_map(function ($cases) use (&$games): void {
            $games[] = $cases['inputs'];
        }, self::hands());

        $this->manager = new GamesManager($games, maxRed: 12, maxGreen: 13, maxBlue: 14);
    }

    public function testComputeSum(): void
    {
        $this->assertEquals(8, $this->manager->computeIds());
    }

    public function testComputePowers(): void
    {
        $this->assertEquals(2286, $this->manager->computePower());
    }

    public function testParseFile(): void
    {
        $games = $this->manager::fileToGames(__DIR__.'/fakeDataset.txt');
        $this->assertEquals([
            [
                ['green' => 1, 'blue' => 4],
                ['red' => 1, 'green' => 2, 'blue' => 1],
                ['red' => 1, 'green' => 1, 'blue' => 2],
                ['red' => 1, 'green' => 1],
                ['green' => 1],
                ['red' => 1, 'green' => 1, 'blue' => 1],
            ],
            [
                ['red' => 2, 'green' => 6, 'blue' => 2],
                ['red' => 1, 'green' => 6, 'blue' => 7],
                ['red' => 1, 'green' => 10, 'blue' => 8],
                ['red' => 2, 'green' => 2, 'blue' => 18],
                ['red' => 1, 'green' => 3, 'blue' => 14],
                ['red' => 1, 'green' => 8, 'blue' => 9],
            ],
            [
                ['red' => 9, 'green' => 6, 'blue' => 5],
                ['red' => 13, 'green' => 1, 'blue' => 4],
                ['red' => 14, 'green' => 9, 'blue' => 1],
            ],
        ], $games);
    }

    /**
     * @return array<string, array{inputs: array{red?: int, green?: int, blue?: int}[], expected: bool}>
     */
    private static function hands(): array
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
                'expected' => true,
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
                'expected' => true,
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
                'expected' => false,
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
                'expected' => false,
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
                'expected' => true,
            ],
        ];
    }
}
