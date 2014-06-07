<?php

namespace Ornithology\Command;

use Symfony\Component\Console as Console;

/**
 * Mark Command
 *
 * This command is intended to put a visual mark beneath a list of tweets that the user has read,
 * as to have a visual reference to 'read tweets'.
 */
class Mark extends Console\Command\Command
{
    /**
     * Configure
     */
    protected function configure()
    {
        $this
            ->setName('mark')
            ->setDescription('Put a visual mark beneath a list of tweets that you have read.');
    }

    /**
     * Execute
     *
     * @param Symfony\Component\Console\Input\InputInterface   $input  The input instance
     * @param Symfony\Component\Console\Output\OutputInterface $output The output instance
     */
    protected function execute(Console\Input\InputInterface $input, Console\Output\OutputInterface $output)
    {
        $output->writeln('#');
        $output->writeln('#');
        $output->writeln('<info>--------------------------------------------------------</info>');
        $output->writeln('#');
        $output->writeln('#');
    }
}

