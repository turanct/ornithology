<?php

namespace Ornithology\Command;

use Symfony\Component\Console as Console;
use Ornithology\Service\Twitter;

/**
 * Refresh Command
 *
 * Get the latest tweets from your twitter timeline
 */
class Refresh extends Console\Command\Command
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
            ->setName('refresh')
            ->setDescription('Get the latest tweets from your twitter timeline.');
    }

    /**
     * Execute
     *
     * @param Symfony\Component\Console\Input\InputInterface   $input  The input instance
     * @param Symfony\Component\Console\Output\OutputInterface $output The output instance
     */
    protected function execute(Console\Input\InputInterface $input, Console\Output\OutputInterface $output)
    {
        try {
            $tweets = $this->twitterService->getLatestTweets();

            foreach ($tweets as $tweet) {
                $output->writeLn('<info>' . $tweet->internal_id . '</info> <comment>' . $tweet->user->name . '</comment>: ' . $tweet->text);
            }
        } catch (\Exception $e) {
            // Noop
        }
    }
}
