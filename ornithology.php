#!/usr/bin/php
<?php

// Require composer autoloader
require_once __DIR__ . '/vendor/autoload.php';

use Symfony\Component\Console as Console;

// Create twitter service
$dataStore = new Ornithology\Service\DataStoreFile(getenv('HOME') . '/.ornithology');
$twitterService = new Ornithology\Service\Twitter($dataStore);

// Initialize the application
$application = new Console\Application('Ornithology', '0.0.2');

// Add application related commands
$application->add(new Ornithology\Command\Quit('exit'));
$application->add(new Ornithology\Command\Mark('mark'));

// Add twitter related commands
$application->add(new Ornithology\Command\Authorize('authorize', $twitterService));
$application->add(new Ornithology\Command\Refresh('refresh', $twitterService));
$application->add(new Ornithology\Command\Tweet('tweet', $twitterService));
$application->add(new Ornithology\Command\Retweet('retweet', $twitterService));

// Set some defaults
$application->setDefaultCommand('mark');

// Run the application as a shell
$shell = new Console\Shell($application);
$shell->run();

