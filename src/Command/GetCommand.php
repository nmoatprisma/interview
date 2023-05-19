<?php

declare(strict_types=1);

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;

#[AsCommand(name: 'app:get')]
class GetCommand extends Command
{
    public function __construct(protected $secretRepository)
    {
        parent::__construct();
    }

    protected function configure()
    {
        $this->addArgument('key', InputArgument::REQUIRED, 'Key of the secret');
    }

    protected function execute($input, $output)
    {
        $output->writeln(
            $this->secretRepository->get(
                $input->getArgument('key')
            ) ?? 'no value'
        );
        return Command::SUCCESS;
    }
}
