<?php

declare(strict_types=1);

namespace App\Tests\Year2023\Day4;

use App\Year2023\Day4\Bingo;
use LogicException;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversDefaultClass \App\Year2023\Day4\Bingo
 */
final class BingoTest extends TestCase
{
    private const FIXTURES = [
        'Anything 1 : 13 32 20 16 61 | ',
        'Anything 2 : 13 32    20 16 61|15     31 17  9',
        'Anything 3:| 61 30 68 82 17 32 24 19',
        'Card 1: 41 48 83 86 17 | 83 86  6 31 17  9 48 53',
        'Card 2: 13 32 20 16 61 | 61 30 68 82 17 32 24 19',
    ];

    public function testInvalidInstantiation(): void
    {
        // Arrange
        $invalidLines = [
            'Case 1 41 48 83 86 17 | 83 86  6 31 17  9 48 53',
            'Case 2: 13 32 20 16 61',
        ];

        // Act
        foreach ($invalidLines as $line) {
            unset($e);
            try {
                new Bingo($line);
            } catch (LogicException $e) {
            }

            // Assert
            $this->assertNotNull($e ?? null);
            $this->assertEquals('Invalid bingo line format !', $e->getMessage());
        }
    }

    public function testValidInstantiation(): void
    {
        foreach (self::FIXTURES as $line) {
            $bingo = new Bingo($line);
            $this->assertInstanceOf(Bingo::class, $bingo);
        }
    }

    public function testInstantiateEmptyWinningNumbers(): void
    {
        $bingo = new Bingo(self::FIXTURES[2]);
        $this->assertEmpty($bingo->winningNumbers());
    }

    public function testSpacesAreRemoved(): void
    {
        $bingo = new Bingo(self::FIXTURES[1]);
        $this->assertEquals([13, 32, 20, 16, 61], $bingo->winningNumbers());
        $this->assertEquals([15, 31, 17, 9], $bingo->myNumbers());
    }

    /**
     * @dataProvider lines
     *
     * @param int[] $winningNumbers
     * @param int[] $myNumbers
     */
    public function testInstantiate(string $line, array $winningNumbers, array $myNumbers): void
    {
        $bingo = new Bingo($line);
        $this->assertEquals($winningNumbers, $bingo->winningNumbers());
        $this->assertEquals($myNumbers, $bingo->myNumbers());
    }

    /**
     * @dataProvider lines
     *
     * @param int[] $winningNumbers
     * @param int[] $myNumbers
     * @param int[] $matches
     */
    public function testMatchingNumbersAndCalculation(
        string $line,
        array $winningNumbers,
        array $myNumbers,
        array $matches,
        int $points,
    ): void {
        $bingo = new Bingo($line);
        $this->assertEquals($matches, $bingo->matches());
        $this->assertEquals($points, $bingo->calculate());
    }

    /**
     * @return array<int, array<int, string|int[]|int>>
     */
    public static function lines(): array
    {
        return [
            [
                'Anything 1 : 13 32 20 16 61 | ',
                [
                    13, 32, 20, 16, 61,
                ],
                [
                ],
                [
                ],
                0,
            ],
            [
                'Anything 2 : 13 32 20 16 61|15     31 17  9',
                [
                    13, 32, 20, 16, 61,
                ],
                [
                    15, 31, 17, 9,
                ],
                [
                ],
                0,
            ],
            [
                'Anything 3:| 61 30 68 82 17 32 24 19',
                [
                ],
                [
                    61, 30, 68, 82, 17, 32, 24, 19,
                ],
                [
                ],
                0,
            ],
            [
                'Card 1: 41 48 83 86 17 | 83 86  6 31 17  9 48 53',
                [
                    41, 48, 83, 86, 17,
                ],
                [
                    83, 86,  6, 31, 17,  9, 48, 53,
                ],
                [
                    17, 48, 83, 86,
                ],
                8,
            ],
            [
                'Card 2: 13 32 20 16 61 | 61 30 68 82 17 32 24 19',
                [
                    13, 32, 20, 16, 61,
                ],
                [
                    61, 30, 68, 82, 17, 32, 24, 19,
                ],
                [
                    32, 61,
                ],
                2,
            ],
        ];
    }
}
