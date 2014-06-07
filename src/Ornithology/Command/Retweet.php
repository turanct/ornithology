<?php

namespace Ornithology\Command;

use Symfony\Component\Console as Console;
use Ornithology\Service\Twitter;

/**
 * Retweet Command
 *
 * Retweet a tweet from your timeline
 */
class Retweet extends Console\Command\Command
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
            ->setName('retweet')
            ->setDescription('Retweet a tweet from your timeline.')
            ->addArgument('tweetId', Console\Input\InputArgument::OPTIONAL, 'Which tweet do we want to retweet?');
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

        $tweet = $this->twitterService->getTweetById($internalId);

        $dialog = $this->getHelperSet()->get('dialog');
        $confirmed = $dialog->askConfirmation(
            $output,
            '<question>Do you want to tweet this? (Y/n)</question>' . "\n" . '"' . $tweet->text . '"' . "\n",
            true
        );

        if ($confirmed === true) {
            $this->twitterService->retweet($internalId);
            $output->writeln('Retweeted...');
        } else {
            $output->writeln('Aborting retweet...');
        }
    }
}

