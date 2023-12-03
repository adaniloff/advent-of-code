<?php

declare(strict_types=1);

namespace App\Year2023\Day3;

use LogicException;

final readonly class EngineSchematicCalculator
{
    public function __construct(private EngineParser $parser) {}

    public function compute(): int
    {
        $keepGoing = true;
        $this->parser->rewind();
        do {
            try {
                $this->parser->tryToMoveCursor();
            } catch (LogicException) {
                $keepGoing = false;
            }
        } while ($keepGoing);

        return array_sum($this->parser->getParts());
    }

    public function computeGearParts(): float
    {
        $keepGoing = true;
        $this->parser->rewind();
        do {
            try {
                $this->parser->tryToMoveCursor('/\*/');
            } catch (LogicException) {
                $keepGoing = false;
            }
        } while ($keepGoing);

        return array_sum($this->parser->getGearParts());
    }
}
