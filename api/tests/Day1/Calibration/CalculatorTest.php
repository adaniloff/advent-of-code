<?php

declare(strict_types=1);

namespace App\Tests\Day1\Calibration;

use App\Day1\Calibration\Calculator;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversDefaultClass \App\Day1\Calibration\Calculator
 */
final class CalculatorTest extends TestCase
{
    /**
     * @dataProvider digitCases
     *
     * @param array{first: int} $expected
     */
    public function testFindFirstDigit(string $input, array $expected): void
    {
        $this->assertEquals($expected['first'], Calculator::first($input));
    }

    /**
     * @dataProvider alphaCases
     *
     * @param array{first: int} $expected
     */
    public function testFindFirstDigitWithStrConversion(string $input, array $expected): void
    {
        $this->assertEquals($expected['first'], Calculator::first($input));
    }

    /**
     * @dataProvider digitCases
     *
     * @param array{last: int} $expected
     */
    public function testFindLastDigit(string $input, array $expected): void
    {
        $this->assertEquals($expected['last'], Calculator::last($input));
    }

    /**
     * @dataProvider alphaCases
     *
     * @param array{last: int} $expected
     */
    public function testFindLastDigitWithStrConversion(string $input, array $expected): void
    {
        $this->assertEquals($expected['last'], Calculator::last($input));
    }

    /**
     * @dataProvider digitCases
     *
     * @param array{first: int, last: int} $expected
     */
    public function testComputeDigit(string $input, array $expected): void
    {
        $this->assertEquals($expected['first'].''.$expected['last'], Calculator::compute($input));
    }

    /**
     * @dataProvider alphaCases
     *
     * @param array{first: int, last: int} $expected
     */
    public function testComputeDigitWithStrConversion(string $input, array $expected): void
    {
        $this->assertEquals($expected['first'].''.$expected['last'], Calculator::compute($input));
    }

    public function testStringOrderPrecedenceIsKept(): void
    {
        // Arrange
        // Act
        // Assert
        $this->assertEquals(8, Calculator::first('eightwothree'));
        $this->assertEquals(3, Calculator::last('eightwothree'));
        $this->assertEquals(3, Calculator::first('threeeightwo'));
        $this->assertEquals(2, Calculator::last('threeeightwo'));
    }

    public function testComputeAll(): void
    {
        // Arrange
        $sum = 0;
        $inputs = [];
        array_map(function ($case) use (&$inputs, &$sum): void {
            $values = array_values($case);
            $inputs[] = $values[0];
            $sum += (int) ($values[1]['first'].''.$values[1]['last']);
        }, self::digitCases());

        // Act
        $result = Calculator::computeAll($inputs);

        // Assert
        $this->assertEquals($sum, $result);
    }

    public function testComputeAllWithStrConversion(): void
    {
        // Arrange
        $sum = 0;
        $inputs = [];
        array_map(function ($case) use (&$inputs, &$sum): void {
            $values = array_values($case);
            $inputs[] = $values[0];
            $sum += (int) ($values[1]['first'].''.$values[1]['last']);
        }, self::alphaCases());

        // Act
        $result = Calculator::computeAll($inputs);

        // Assert
        $this->assertEquals($sum, $result);
    }

    /**
     * @return array<string, array{input: string, expected: array{first: int, last: int}}>
     */
    public static function digitCases(): array
    {
        return [
            '1abc2' => [
                'input' => '1abc2',
                'expected' => [
                    'first' => 1,
                    'last' => 2,
                ],
            ],
            'pqr3stu8vwx' => [
                'input' => 'pqr3stu8vwx',
                'expected' => [
                    'first' => 3,
                    'last' => 8,
                ],
            ],
            'a1b2c3d4e5f' => [
                'input' => 'a1b2c3d4e5f',
                'expected' => [
                    'first' => 1,
                    'last' => 5,
                ],
            ],
            'treb7uchet' => [
                'input' => 'treb7uchet',
                'expected' => [
                    'first' => 7,
                    'last' => 7,
                ],
            ],
            'sans_value' => [
                'input' => 'sans_value',
                'expected' => [
                    'first' => 0,
                    'last' => 0,
                ],
            ],
        ];
    }

    /**
     * @return array<string, array{input: string, expected: array{first: int, last: int}}>
     */
    public static function alphaCases(): array
    {
        return [
            'two1nine' => [
                'input' => 'two1nine',
                'expected' => [
                    'first' => 2,
                    'last' => 9,
                ],
            ],
            'eightwothree' => [
                'input' => 'eightwothree',
                'expected' => [
                    'first' => 8,
                    'last' => 3,
                ],
            ],
            'abcone2threexyz' => [
                'input' => 'abcone2threexyz',
                'expected' => [
                    'first' => 1,
                    'last' => 3,
                ],
            ],
            'xtwone3four' => [
                'input' => 'xtwone3four',
                'expected' => [
                    'first' => 2,
                    'last' => 4,
                ],
            ],
            '4nineeightseven2' => [
                'input' => '4nineeightseven2',
                'expected' => [
                    'first' => 4,
                    'last' => 2,
                ],
            ],
            'zoneight234' => [
                'input' => 'zoneight234',
                'expected' => [
                    'first' => 1,
                    'last' => 4,
                ],
            ],
            '7pqrstsixteen' => [
                'input' => '7pqrstsixteen',
                'expected' => [
                    'first' => 7,
                    'last' => 6,
                ],
            ],
            'sans_value' => [
                'input' => 'sans_value',
                'expected' => [
                    'first' => 0,
                    'last' => 0,
                ],
            ],
        ];
    }
}
