<?php

namespace Ornithology\Command;

use Symfony\Component\Console as Console;
use Ornithology\Service\Twitter;

/**
 * Refresh Command
 *
 * Get the latest tweets from your twitter timeline
 */
class Urls extends Console\Command\Command
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
            ->setName('urls')
            ->setDescription('Get the latest urls from your twitter timeline.');
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
            $urls = $this->twitterService->getLatestUrls();

            foreach ($urls as $url) {
                $output->writeLn('<info> - </info> <comment>' . $url . '</comment>');
            }
        } catch (\Exception $e) {
            // Noop
        }
    }
}
