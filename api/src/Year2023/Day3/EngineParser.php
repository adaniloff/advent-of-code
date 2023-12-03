<?php

declare(strict_types=1);

namespace App\Year2023\Day3;

use LogicException;

/**
 * @phpstan-type Position array{0: int, 1: int}
 */
final class EngineParser
{
    /**
     * @var Position
     */
    private array $cursor;
    private readonly int $height;
    private readonly int $length;

    /**
     * @var array<string, int>
     */
    private array $parts = [];

    /**
     * @var array<int, int|float>
     */
    private array $gearParts = [];

    /**
     * @var string[]
     */
    private array $rewindableSchema;

    /**
     * @param string[] $schema
     */
    public function __construct(private readonly array $schema)
    {
        $this->validateOrThrow();
        $this->rewindableSchema = $this->schema;
        $this->height = count($this->schema);
        $this->length = strlen($this->schema[0]);
        $this->cursor = [0, 0];
    }

    /**
     * @return Position
     */
    public function getBoundaries(): array
    {
        return [
            $this->height,
            $this->length,
        ];
    }

    /**
     * @return Position
     */
    public function getCursorPosition(): array
    {
        return $this->cursor;
    }

    /**
     * @return int[]
     */
    public function getParts(): array
    {
        ksort($this->parts);

        return $this->parts;
    }

    /**
     * @return array<int, float|int>
     */
    public function getGearParts(): array
    {
        ksort($this->gearParts);

        return $this->gearParts;
    }

    public function tryToMoveCursor(?string $pattern = '/[^0-9.\s]/'): self
    {
        do {
            preg_match($pattern, $this->rewindableSchema[$this->cursor[0]], $matches, PREG_OFFSET_CAPTURE);
            if (count($matches)) {
                $this->moveCursorAt((int) $matches[0][1]);
                $this->addAdjacentParts();
                $this->addGearParts();

                return $this;
            }
        } while ($this->nextLine());

        throw new LogicException('Cannot be moved !');
    }

    public function rewind(): void
    {
        $this->rewindableSchema = $this->schema;
        $this->cursor = [0, 0];
        $this->parts = [];
        $this->gearParts = [];
    }

    private function validateOrThrow(): void
    {
        $lineLength = strlen($this->schema[0] ?? '');
        if (!$lineLength || !empty(array_filter($this->schema, function (string $line) use ($lineLength) {
            return $lineLength !== strlen($line);
        }))) {
            throw new LogicException('This engine schema is not valid.');
        }
    }

    private function nextLine(): bool
    {
        if (!($this->rewindableSchema[++$this->cursor[0]] ?? null)) {
            return false;
        }

        return true;
    }

    private function moveCursorAt(int $position): void
    {
        $this->rewindableSchema[$this->cursor[0]][$position] = ' ';
        $this->cursor[1] = $position;
    }

    private function addAdjacentParts(): void
    {
        $this->buildParts(existingParts: $this->parts);
    }

    private function addGearParts(): void
    {
        $parts = [];
        $this->buildParts(existingParts: $parts);
        if (2 == count($parts)) {
            $keys = array_keys($parts);
            $this->gearParts[] = $parts[$keys[0]] * $parts[$keys[1]];
        }
    }

    /**
     * @param array<string, int|float> $existingParts
     */
    private function buildParts(array &$existingParts): void
    {
        $line = $this->cursor[0] - 1;
        for ($parsedLines = 0; $parsedLines <= 2; ++$parsedLines) {
            if ($line < 0 || $line >= $this->height) {
                continue;
            }
            $char = $this->cursor[1] - 1;
            for ($parsedLetters = 0; $parsedLetters <= 2; ++$parsedLetters) {
                if ($char < 0 || $char >= $this->length) {
                    continue;
                }
                $case = $this->rewindableSchema[$line][$char];
                if (is_numeric($case) && !isset($existingParts[$line.'_'.$char])) {
                    $firstCharPosition = $lastCharPosition = $char;
                    $number = 0;
                    do {
                        $number += pow(10, $char - $firstCharPosition)
                            * ((int) $this->rewindableSchema[$line][$firstCharPosition]);
                    } while (
                        isset($this->rewindableSchema[$line][--$firstCharPosition])
                        && is_numeric($this->rewindableSchema[$line][$firstCharPosition])
                    );
                    while (
                        isset($this->rewindableSchema[$line][++$lastCharPosition])
                        && is_numeric($this->rewindableSchema[$line][$lastCharPosition])
                    ) {
                        $number *= 10;
                        $number += (int) $this->rewindableSchema[$line][$lastCharPosition];
                    }
                    $existingParts[$line.'_'.$firstCharPosition] = $number;
                }
                ++$char;
            }
            ++$line;
        }
    }
}
