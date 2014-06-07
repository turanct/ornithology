<?php

namespace Ornithology\Service;

/**
 * Interface for storage related operations for our twitter service class
 */
interface DataStore
{
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
    public function getConsumer();

    /**
     * Get the OAuth access token
     *
     * @return ZendOAuth\Token\Access The access token
     */
    public function getAccessToken();

    /**
     * Save the OAuth access token
     *
     * @param ZendOAuth\Token\Access $accessToken The access token
     */
    public function setAccessToken($accessToken);

    /**
     * Get the twitter tweet id from the last tweet we received
     *
     * @return string The tweet id from the last tweet we received
     */
    public function getLastTweetId();

    /**
     * Save a twitter tweet id as the last id we received
     *
     * @param string $tweetId The last tweet's id
     */
    public function setLastTweetId($tweetId);

    /**
     * Persist a list of tweet objects, and assign an internal id to them, so that we can references them later
     *
     * @param array $tweets A list of tweet objects that we want to persist
     *
     * @return array The same list of tweets, but the objects now also have an internal_id property
     */
    public function persistTweetList(array $tweets);

    /**
     * Get a tweet from the persisted tweet list, by its internal id
     *
     * @param string $internalId The internal id of the persisted tweet
     *
     * @return stdClass A tweet object or null
     */
    public function getTweetByInternalId($internalId);
}
