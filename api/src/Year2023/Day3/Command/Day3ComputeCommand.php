<?php

declare(strict_types=1);

namespace App\Year2023\Day3\Command;

use App\FileReader\Reader;
use App\Year2023\Day3\{EngineParser, EngineSchematicCalculator};
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\{InputInterface};
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'aoc:2023:day3',
    description: 'Resolve the day3 puzzle: https://adventofcode.com/2023/day/3',
)]
final class Day3ComputeCommand extends Command
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $engineCalculator = new EngineSchematicCalculator(new EngineParser(Reader::toArray(__DIR__.'/../dataset.txt')));

        $io->success(sprintf('The computed value is %s', $engineCalculator->compute()));
        $io->success(sprintf('The gear parts count is %s', $engineCalculator->computeGearParts()));

        return Command::SUCCESS;
    }
}
