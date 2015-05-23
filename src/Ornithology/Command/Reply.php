<?php

namespace Ornithology\Command;

use Symfony\Component\Console as Console;
use Ornithology\Service\Twitter;

/**
 * Reply Command
 *
 * Reply to a tweet
 */
class Reply extends Console\Command\Command
{
    /**
     * @var Ornithology\Service\Twitter The twitter service
     */
    protected $twitterService;

    /**
     * Constructor
     *
     * @param string                      $name           The command name
     * @param Ornithology\Service\Twitter $twitterService The twitter service
     */
    public function __construct($name, Twitter $twitterService) {
        parent::__construct($name);

        $this->twitterService = $twitterService;
    }

    /**
     * Configure
     */
    protected function configure()
    {
        $this
            ->setName('reply')
            ->setDescription('Reply to a tweet.')
            ->addArgument('tweetId', Console\Input\InputArgument::OPTIONAL, 'Which tweet do we want to reply to?');
    }

    /**
     * Execute
     *
     * @param Symfony\Component\Console\Input\InputInterface   $input  The input instance
     * @param Symfony\Component\Console\Output\OutputInterface $output The output instance
     */
    protected function execute(Console\Input\InputInterface $input, Console\Output\OutputInterface $output)
    {
        $internalId = $input->getArgument('tweetId');

        $dialog = $this->getHelperSet()->get('dialog');
        $tweet = $dialog->ask(
            $output,
            '<question>What do you want to reply?</question>' . "\n",
            ''
        );

        $tweet = trim($tweet);

        if (empty($tweet)) {
            $output->writeln('Aborting tweet...');

            return;
        }

        $confirmed = $dialog->askConfirmation(
            $output,
            '<question>Do you want to tweet this? (Y/n)</question>' . "\n" . '"' . $tweet . '"' . "\n",
            true
        );

        if ($confirmed === true) {
            $this->twitterService->reply($internalId, $tweet);
            $output->writeln('Tweeted...');
        } else {
            $output->writeln('Aborting tweet...');
        }
    }
}

