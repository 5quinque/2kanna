<?php

namespace App\Command;

use App\Service\DeleteOldPosts;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DeleteOldPostsCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:delete-old-posts';

    private $service;

    public function __construct(DeleteOldPosts $service)
    {
        $this->service = $service;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Remove posts not updated for more than 7 days')
            ->setHelp('This command deletes old posts');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Deleting Old Posts');
        $output->writeln('==================');

        $serviceOutput = $this->service->findOldPosts();
        $output->writeln($serviceOutput);
    }
}