<?php

declare(strict_types=1);

namespace App\Year2023\Day4\Command;

use App\FileReader\Reader;
use App\Year2023\Day4\BingoCalculator;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\{InputInterface};
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'aoc:2023:day4',
    description: 'Resolve the day4 puzzle: https://adventofcode.com/2023/day/4',
)]
final class Day4ComputeCommand extends Command
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $data = Reader::toArray(__DIR__.'/../dataset.txt');
        $calculator = new BingoCalculator($data);

        $io->success(sprintf('The computed value is %s', $calculator->compute()));
        $io->success(sprintf('The scratch cards count is %s', $calculator->computeScratchCards()));

        return Command::SUCCESS;
    }
}
