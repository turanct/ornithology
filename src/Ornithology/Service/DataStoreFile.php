<?php

namespace Ornithology\Service;

/**
 * DataStoreFile
 *
 * This class will be used to persist our data to files, in a simple quick & dirty
 * way. Its intention is to have something up and working quickly. We might want to
 * clean a lot of things up later...
 */
class DataStoreFile implements DataStore
{
    protected $baseDir;

    /**
     * Construct
     *
     * @param string $baseDir The path where we will store files for persistence
     */
    public function __construct($baseDir)
    {
        $this->baseDir = $baseDir;

        if (!file_exists($baseDir) || !is_dir($baseDir)) {
            if (mkdir($baseDir) === false) {
                throw new InvalidArgumentException('The configuration directory could not be created.');
            }
        }
    }

    /**
     * Get an array with the consumer key and secret
     *
     * The array will look like this:
     *     array(
     *         'consumerKey' => 'consumer key goes here',
     *         'consumerSecret' => 'consumer secret goes here',
     *     )
     *
     * @return array
     */
    public function getConsumer()
    {
        $file = $this->baseDir . '/Consumer.php';

        if (!file_exists($file)) {
            return null;
        }

        $consumer = require($file);

        return $consumer;
    }

    /**
     * Get the OAuth access token
     *
     * @return ZendOAuth\Token\Access The access token
     */
    public function getAccessToken()
    {
        $file = $this->baseDir . '/access.token';

        if (!file_exists($file)) {
            return null;
        }

        $accessToken = unserialize(file_get_contents($file));

        return $accessToken;
    }

    /**
     * Save the OAuth access token
     *
     * @param ZendOAuth\Token\Access $accessToken The access token
     */
    public function setAccessToken($accessToken)
    {
        $file = $this->baseDir . '/access.token';

        file_put_contents($file, serialize($accessToken));
    }

    /**
     * Get the twitter tweet id from the last tweet we received
     *
     * @return string The tweet id from the last tweet we received
     */
    public function getLastTweetId()
    {
        $file = $this->baseDir . '/lastTweet.id';

        if (!file_exists($file)) {
            return null;
        }

        $lastTweetId = file_get_contents($file);

        return $lastTweetId;
    }

    /**
     * Save a twitter tweet id as the last id we received
     *
     * @param string $tweetId The last tweet's id
     */
    public function setLastTweetId($lastTweetId)
    {
        $file = $this->baseDir . '/lastTweet.id';

        file_put_contents($file, $lastTweetId);
    }

    /**
     * Persist a list of tweet objects, and assign an internal id to them, so that we can references them later
     *
     * @param array $tweets A list of tweet objects that we want to persist
     *
     * @return array The same list of tweets, but the objects now also have an internal_id property
     */
    public function persistTweetList(array $tweets)
    {
        $letters = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'L', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z');
        $numbers = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9);
        $indexes = array(' ');
        foreach ($letters as $letter) {
            foreach ($numbers as $number) {
                $indexes[] = $letter . $number;
            }
        }
        reset($indexes);

        $this->tweetList = array();
        foreach ($tweets as $key => $tweet) {
            $tweet->internal_id = next($indexes);

            $this->tweetList[] = $tweet;
            $tweets[$key] = $tweet;
        }

        return $tweets;
    }

    /**
     * Get a tweet from the persisted tweet list, by its internal id
     *
     * @param string $internalId The internal id of the persisted tweet
     *
     * @return stdClass A tweet object or null
     */
    public function getTweetByInternalId($internalId)
    {
        foreach ((array) $this->tweetList as $tweet) {
            if ($tweet->internal_id === $internalId) {
                return $tweet;
            }
        }

        return null;
    }

    /**
     * Get the persisted tweet list
     *
     * @return array
     */
    public function getTweetList()
    {
        return $this->tweetList;
    }
}

