<?php

declare(strict_types=1);

namespace App\Command;

use App\Day1\Calibration\Calculator;
use RuntimeException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\{InputArgument, InputInterface, InputOption};
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:day1:compute',
    description: 'Compute a value from a list of strings: https://adventofcode.com/2023/day/1',
)]
class Day1ComputeCommand extends Command
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->success(sprintf('The computed value is %s', Calculator::computeAll($this->dataset())));

        return Command::SUCCESS;
    }

    /**
     * @return string[]
     */
    private function dataset(): array
    {
        $filename = __DIR__.'/../Datasets/Day1/day1.txt';
        $file = fopen($filename, 'r');
        $size = filesize($filename);
        if (!$file || !$size) {
            throw new RuntimeException('Unable to open the file');
        }

        $content = fread($file, $size) ?: '';

        return explode("\n", $content);
    }
}
