<?php

namespace Ornithology\Command;

use Symfony\Component\Console as Console;
use Ornithology\Service\Twitter;

/**
 * Authorize Command
 *
 * Authorize the client to use your twitter profile
 */
class Authorize extends Console\Command\Command
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
            ->setName('authorize')
            ->setDescription('Authorize the client to user your twitter profile.');
    }

    /**
     * Execute
     *
     * @param Symfony\Component\Console\Input\InputInterface   $input  The input instance
     * @param Symfony\Component\Console\Output\OutputInterface $output The output instance
     */
    protected function execute(Console\Input\InputInterface $input, Console\Output\OutputInterface $output)
    {
        $output->writeln('You will be redirected to twitter...');

        passthru('open "' . $this->twitterService->requestAuthorization() . '"');

        $dialog = $this->getHelperSet()->get('dialog');
        $pinCode = $dialog->ask(
            $output,
            'Enter your pin-code: ',
            null
        );

        $accessToken = $this->twitterService->authorize($pinCode);
    }
}

