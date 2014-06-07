<?php

namespace Ornithology\Command;

use Symfony\Component\Console as Console;

/**
 * Quit Command
 *
 * Shut down the application
 */
class Quit extends Console\Command\Command
{
    /**
     * Configure
     */
    protected function configure()
    {
        $this
            ->setName('quit')
            ->setDescription('Shut down the application.');
    }

    /**
     * Execute
     *
     * @param Symfony\Component\Console\Input\InputInterface   $input  The input instance
     * @param Symfony\Component\Console\Output\OutputInterface $output The output instance
     */
    protected function execute(Console\Input\InputInterface $input, Console\Output\OutputInterface $output)
    {
        $output->writeln('Quitting...');
        exit;
    }
}

