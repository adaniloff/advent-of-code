<?php

declare(strict_types=1);

namespace App\Tests\Year2023\Day3;

use App\FileReader\Reader;
use App\Year2023\Day3\EngineParser;
use LogicException;
use PHPUnit\Framework\TestCase;
use ReflectionProperty;

/**
 * @internal
 *
 * @coversDefaultClass \App\Year2023\Day3\EngineParser
 */
final class EngineParserTest extends TestCase
{
    public function testInvalidSchemaThrows(): void
    {
        $this->expectExceptionMessage('This engine schema is not valid.');
        $schema = Reader::toArray(__DIR__.'/fakeWrongDataset.txt');
        new EngineParser($schema);
    }

    public function testEmptySchemaThrows(): void
    {
        $this->expectExceptionMessage('This engine schema is not valid.');
        new EngineParser([]);
    }

    public function testValidSchemaInitialize(): void
    {
        $schema = Reader::toArray(__DIR__.'/fakeDataset.txt');
        $engine = new EngineParser($schema);
        $this->assertEquals([0, 0], $engine->getCursorPosition());
    }

    public function testBoundaries(): void
    {
        $schema = Reader::toArray(__DIR__.'/fakeDataset.txt');
        $engine = new EngineParser($schema);
        $this->assertEquals([10, 10], $engine->getBoundaries());
    }

    public function testMoveCursorOnceWorks(): void
    {
        $engine = new EngineParser([
            '...1...2...3',
            '...4...*...6',
            '...7...8...9',
        ]);
        $engine->tryToMoveCursor();
        $this->assertEquals([
            1, 7,
        ], $engine->getCursorPosition());
    }

    public function testMoveCursorTwiceOnSameLineWorks(): void
    {
        $engine = new EngineParser([
            '...1...2...3',
            '...4...**..6',
            '...*...8...9',
        ]);
        $engine
            ->tryToMoveCursor()
            ->tryToMoveCursor()
        ;
        $this->assertEquals([
            1, 8,
        ], $engine->getCursorPosition());
    }

    public function testMoveCursorNextLineWorks(): void
    {
        $engine = new EngineParser([
            '...1...2...3',
            '...4...*...6',
            '...*...8...9',
        ]);
        $engine
            ->tryToMoveCursor()
            ->tryToMoveCursor()
        ;
        $this->assertEquals([
            2, 3,
        ], $engine->getCursorPosition());
    }

    public function testMoveCursorComplexScenario(): void
    {
        $engine = new EngineParser([
            '!..1...2...3',
            '...4..5*6..6',
            '...7$*..8..9',
            '10+.........',
        ]);
        $keepGoing = true;
        $positions = [];

        do {
            try {
                $engine->tryToMoveCursor();
                $positions[] = $engine->getCursorPosition();
            } catch (LogicException) {
                $keepGoing = false;
            }
        } while ($keepGoing);

        $this->assertEquals([
            [0, 0],
            [1, 7],
            [2, 4],
            [2, 5],
            [3, 2],
        ], $positions);
    }

    public function testNoNumbersFound(): void
    {
        $engine = new EngineParser([
            '!..1.*.2....3',
            '...4...5..6..',
        ]);
        $keepGoing = true;

        do {
            try {
                $engine->tryToMoveCursor();
            } catch (LogicException) {
                $keepGoing = false;
            }
        } while ($keepGoing);

        $this->assertEquals(0, array_sum($engine->getParts()));
    }

    public function testNumbersIsNotCountedTwice(): void
    {
        $engine = new EngineParser([
            '...1...2....3',
            '...4..5**6..6',
        ]);
        $keepGoing = true;

        do {
            try {
                $engine->tryToMoveCursor();
            } catch (LogicException) {
                $keepGoing = false;
            }
        } while ($keepGoing);

        $this->assertEquals(13, array_sum($engine->getParts()));
    }

    public function testComplexNumbersWorksFine(): void
    {
        $engine = new EngineParser([
            '...1...21...3.!',
            '...4.15**6..6..',
        ]);
        $keepGoing = true;

        do {
            try {
                $engine->tryToMoveCursor();
            } catch (LogicException) {
                $keepGoing = false;
            }
        } while ($keepGoing);

        $this->assertEquals(42, array_sum($engine->getParts()));
    }

    public function testExtraComplexNumbersWorksFine(): void
    {
        $engine = new EngineParser([
            '..........................380.......................143............................108.............630...........425...................',
            '....*585..30....217*616..........$...................../....$.................447...........381..................+..........973........',
            '.210......*...............639...541..-........830*...........912..........743*.......................828..671........+......*..........',
            '.......760....$..............*........737.*.......949..568.......................=........628.85........&.#..........87...535.....794..',
            '....#......616..........373.999..392......853..........&.........666.......*.....365.............807............@....................*.',
            '.680................800*..............684................329....*.......960.186........725........*......&.....631.....700*818.........',
            '............-402...........%.........@.........576.............956................../.....*....237....490........................998...',
        ]);
        $keepGoing = true;

        do {
            try {
                $engine->tryToMoveCursor();
            } catch (LogicException) {
                $keepGoing = false;
            }
        } while ($keepGoing);

        $this->assertEquals(
            143 + 425
            + 585 + 30 + 447 + 217 + 616 + 973
            + 210 + 639 + 541 + 830 + 912 + 743 + 828 + 671
            + 760 + 737 + 949 + 568 + 87 + 535 + 794
            + 616 + 373 + 999 + 853 + 666 + 365 + 807
            + 680 + 800 + 684 + 960 + 186 + 725 + 631 + 700 + 818
            + 402 + 956 + 237 + 490, array_sum($engine->getParts()));
        $this->assertEquals(
            (585 * 210) + (217 * 616)
            + (30 * 760) + (830 * 949) + (743 * 447) + (973 * 535)
            + (639 * 999) + (800 * 373) + (666 * 956)
            + (960 * 186) + (807 * 237) + (700 * 818), array_sum($engine->getGearParts()));
    }

    public function testRewindCursor(): void
    {
        $schema = [
            '!..1...2...3',
            '...4..5*6..6',
            '...7$*..8..9',
            '10+.........',
        ];
        $engine = new EngineParser($schema);
        $keepGoing = true;

        do {
            try {
                $engine->tryToMoveCursor();
            } catch (LogicException) {
                $keepGoing = false;
            }
        } while ($keepGoing);
        $this->assertNotEquals([], $engine->getParts());
        $engine->rewind();

        $reflection = new ReflectionProperty($engine, 'schema');
        $newSchema = $reflection->getValue($engine);
        $this->assertEquals($schema, $newSchema);
        $this->assertEquals([0, 0], $engine->getCursorPosition());
        $this->assertEquals([], $engine->getParts());
        $this->assertEquals([], $engine->getGearParts());
    }

    public function testMoveCursorFails(): void
    {
        $this->expectExceptionMessage('Cannot be moved !');
        $engine = new EngineParser([
            '...1...2...3',
            '...4...5...6',
            '...7...8...9',
        ]);
        $engine->tryToMoveCursor();
    }
}
