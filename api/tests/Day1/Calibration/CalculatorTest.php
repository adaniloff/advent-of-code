<?php

declare(strict_types=1);

namespace App\Tests\Day1\Calibration;

use App\Day1\Calibration\Calculator;
use PHPUnit\Framework\TestCase;

final class CalculatorTest extends TestCase
{
    public function testFindFirstDigit(): void
    {
        $this->assertEquals(1, Calculator::first("1abc2"));
        $this->assertEquals(3, Calculator::first("pqr3stu8vwx"));
        $this->assertEquals(1, Calculator::first("a1b2c3d4e5f"));
        $this->assertEquals(7, Calculator::first("treb7uchet"));
        $this->assertEquals(0, Calculator::first("sans_value"));
    }

    public function testFindLastDigit(): void
    {
        $this->assertEquals(2, Calculator::last("1abc2"));
        $this->assertEquals(8, Calculator::last("pqr3stu8vwx"));
        $this->assertEquals(5, Calculator::last("a1b2c3d4e5f"));
        $this->assertEquals(7, Calculator::last("treb7uchet"));
        $this->assertEquals(0, Calculator::last("sans_value"));
    }

    public function testCompute(): void
    {
        $this->assertEquals(12, Calculator::compute("1abc2"));
        $this->assertEquals(38, Calculator::compute("pqr3stu8vwx"));
        $this->assertEquals(15, Calculator::compute("a1b2c3d4e5f"));
        $this->assertEquals(77, Calculator::compute("treb7uchet"));
        $this->assertEquals(0, Calculator::compute("sans_value"));
    }

    public function testComputeAll(): void
    {
        $this->assertEquals(12 + 38 + 15 + 77, Calculator::computeAll(["1abc2", "pqr3stu8vwx", "a1b2c3d4e5f", "treb7uchet", "sans_value"]));
    }
}
