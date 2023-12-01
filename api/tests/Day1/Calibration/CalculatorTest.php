<?php

declare(strict_types=1);

namespace App\Tests\Day1\Calibration;

use App\Day1\Calibration\Calculator;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
final class CalculatorTest extends TestCase
{
    public function testFindFirstDigitOnlyDigit(): void
    {
        $this->markTestIncomplete('AAA must be implemented.');

        $this->assertEquals(1, Calculator::first('1abc2'));
        $this->assertEquals(3, Calculator::first('pqr3stu8vwx'));
        $this->assertEquals(1, Calculator::first('a1b2c3d4e5f'));
        $this->assertEquals(7, Calculator::first('treb7uchet'));
        $this->assertEquals(0, Calculator::first('sans_value'));
    }

    public function testFindFirstDigitMixedValues(): void
    {
        $this->markTestIncomplete('AAA must be implemented.');

        $this->assertEquals(2, Calculator::first('two1nine'));
        $this->assertEquals(8, Calculator::first('eightwothree'));
        $this->assertEquals(1, Calculator::first('abcone2threexyz'));
        $this->assertEquals(2, Calculator::first('xtwone3four'));
        $this->assertEquals(4, Calculator::first('4nineeightseven2'));
        $this->assertEquals(1, Calculator::first('zoneight234'));
        $this->assertEquals(7, Calculator::first('7pqrstsixteen'));
        $this->assertEquals(0, Calculator::first('sans_value'));
    }

    public function testFindLastDigitMixedValues(): void
    {
        $this->markTestIncomplete('AAA must be implemented.');

        $this->assertEquals(9, Calculator::last('two1nine'));
        $this->assertEquals(3, Calculator::last('eightwothree'));
        $this->assertEquals(3, Calculator::last('abcone2threexyz'));
        $this->assertEquals(4, Calculator::last('xtwone3four'));
        $this->assertEquals(2, Calculator::last('4nineeightseven2'));
        $this->assertEquals(4, Calculator::last('zoneight234'));
        $this->assertEquals(6, Calculator::last('7pqrstsixteen'));
        $this->assertEquals(0, Calculator::last('sans_value'));
    }

    public function testStringPrecedenceIsKept(): void
    {
        $this->assertEquals(8, Calculator::first('eightwothree'));
    }

    public function testComputeMixedValues(): void
    {
        $this->markTestIncomplete('AAA must be implemented.');

        $this->assertEquals(29, Calculator::compute('two1nine'));
        $this->assertEquals(83, Calculator::compute('eightwothree'));
        $this->assertEquals(13, Calculator::compute('abcone2threexyz'));
        $this->assertEquals(24, Calculator::compute('xtwone3four'));
        $this->assertEquals(42, Calculator::compute('4nineeightseven2'));
        $this->assertEquals(14, Calculator::compute('zoneight234'));
        $this->assertEquals(76, Calculator::compute('7pqrstsixteen'));
        $this->assertEquals(0, Calculator::compute('sans_value'));
    }

    public function testComputeOnlyDigit(): void
    {
        $this->markTestIncomplete('AAA must be implemented.');

        $this->assertEquals(12, Calculator::compute('1abc2'));
        $this->assertEquals(38, Calculator::compute('pqr3stu8vwx'));
        $this->assertEquals(15, Calculator::compute('a1b2c3d4e5f'));
        $this->assertEquals(77, Calculator::compute('treb7uchet'));
        $this->assertEquals(0, Calculator::compute('sans_value'));
    }

    public function testComputeAllOnlyDigit(): void
    {
        $this->assertEquals(12 + 38 + 15 + 77, Calculator::computeAll(['1abc2', 'pqr3stu8vwx', 'a1b2c3d4e5f', 'treb7uchet', 'sans_value']));
    }

    public function testComputeAllMixedValues(): void
    {
        $this->assertEquals(29 + 83 + 13 + 24 + 42 + 14 + 76, Calculator::computeAll(['two1nine', 'eightwothree', 'abcone2threexyz', 'xtwone3four', '4nineeightseven2', 'zoneight234', '7pqrstsixteen', 'sans_value']));
    }
}
