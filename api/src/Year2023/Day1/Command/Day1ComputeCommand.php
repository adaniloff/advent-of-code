<?php

declare(strict_types=1);

namespace App\Year2023\Day1\Command;

use App\FileReader\Reader;
use App\Year2023\Day1\Calibration\Calculator;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\{InputInterface};
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'aoc:2023:day1',
    description: 'Resolve the day1 puzzle: https://adventofcode.com/2023/day/1',
)]
final class Day1ComputeCommand extends Command
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->success(sprintf('The computed value is %s', Calculator::computeAll(Reader::toArray(__DIR__.'/../dataset.txt'))));

        return Command::SUCCESS;
    }
}
