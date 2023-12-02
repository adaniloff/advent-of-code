<?php

declare(strict_types=1);

namespace App\Tests\Year2023\Day2;

use App\Year2023\Day2\Keeper;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversDefaultClass \App\Year2023\Day2\Keeper
 */
final class KeeperTest extends TestCase
{
    public function testValidateEmptyHands(): void
    {
        $keeper = new Keeper([]);
        $this->assertEquals(true, $keeper->hasEnoughCubes(maxRed: 12, maxGreen: 13, maxBlue: 14));
    }

    public function testEstimateEmptyHands(): void
    {
        $keeper = new Keeper([]);
        $this->assertEquals(
            ['red' => 0, 'green' => 0, 'blue' => 0],
            $keeper->handful
        );
    }

    public function testValidateSingleValidHandWithMissingColor(): void
    {
        $keeper = new Keeper([['blue' => 1, 'red' => 3]]);
        $this->assertEquals(true, $keeper->hasEnoughCubes(maxRed: 12, maxGreen: 13, maxBlue: 14));
    }

    public function testEstimateSingleValidHandWithMissingColor(): void
    {
        // Arrange
        // Act
        // Assert
        $this->assertEquals(
            ['red' => 3, 'green' => 0, 'blue' => 1],
            (new Keeper([['blue' => 1, 'red' => 3]]))->handful
        );
        $this->assertEquals(
            ['red' => 1, 'green' => 2, 'blue' => 0],
            (new Keeper([['green' => 2, 'red' => 1]]))->handful
        );
        $this->assertEquals(
            ['red' => 4, 'green' => 0, 'blue' => 4],
            (new Keeper([['blue' => 4, 'red' => 4]]))->handful
        );
    }

    public function testSingleInvalidHand(): void
    {
        $keeper = new Keeper([['blue' => 15, 'red' => 3]]);
        $this->assertEquals(false, $keeper->hasEnoughCubes(maxRed: 12, maxGreen: 13, maxBlue: 14));
    }

    /**
     * @dataProvider hands
     *
     * @param array{red?: int, green?: int, blue?: int}[] $hands
     */
    public function testHandValidation(array $hands, bool $expected): void
    {
        $keeper = new Keeper($hands);
        $this->assertEquals($expected, $keeper->hasEnoughCubes(maxRed: 12, maxGreen: 13, maxBlue: 14));
    }

    /**
     * @return array<string, array{inputs: array{red?: int, green?: int, blue?: int}[], expected: bool}>
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
