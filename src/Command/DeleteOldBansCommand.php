<?php

namespace App\Command;

use App\Service\BannedIP;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DeleteOldBansCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:delete-old-bans';

    private $service;

    public function __construct(BannedIP $service)
    {
        $this->service = $service;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Remove bans')
            ->setHelp('This command deletes old bans')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Deleting Old Bans');
        $output->writeln('=================');

        $serviceOutput = $this->service->findOldBans();
        $output->writeln($serviceOutput);

        return Command::SUCCESS;
    }
}
