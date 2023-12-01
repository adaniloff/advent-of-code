<?php

declare(strict_types=1);

namespace App\Tests\FileReader;

use App\FileReader\Reader;
use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 * @internal
 *
 * @coversDefaultClass \App\FileReader\Reader
 */
final class ReaderTest extends TestCase
{
    public function testMissingFileThrows(): void
    {
        $this->expectExceptionMessage('Unable to open the file');
        Reader::content(__DIR__.'/missing-file.txt');
    }

    public function testContent(): void
    {
        $expected = <<<TEXT
hello world
this is a simple test ;)
TEXT;
        $this->assertEquals($expected, Reader::content(__DIR__.'/dummy.txt'));
    }

    /**
     * @covers ::toArray
     */
    public function testToArrayWorks(): void
    {
        $this->assertEquals(
            ['hello world', 'this is a simple test ;)'],
            Reader::toArray(__DIR__.'/dummy.txt')
        );
    }
}
