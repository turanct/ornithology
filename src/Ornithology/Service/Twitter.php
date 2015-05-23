<?php

namespace Ornithology\Service;

use InvalidArgumentException;

/**
 * Twitter Service
 *
 * This is the Twitter Service class, which will help us communicate with Zend's
 * ZendService\Twitter class. It also 'remembers' our latest tweets, so that we can
 * interact with them.
 */
class Twitter
{
    /**
     * @var DataStore The datastore
     */
    protected $dataStore;

    /**
     * @var Ornithology\Client\Twitter The twitter client, extended from ZendService\Twitter
     */
    protected $client;

    /**
     * @var ZendOauth\Token\Request The request token object
     */
    protected $requestToken;

    /**
     * @var ZendOauth\Token\Access The access token object
     */
    protected $accessToken;

    /**
     * @var bool Are we logged in?
     */
    protected $loggedIn = false;

    /**
     * Constructor
     *
     * @param DataStore $dataStore The datastore
     */
    public function __construct(DataStore $dataStore)
    {
        $this->dataStore = $dataStore;

        if ($this->dataStore->getAccessToken() !== null) {
            $this->login($this->dataStore->getAccessToken(), $this->dataStore->getConsumer());
        }
    }

    /**
     * Request authorization
     *
     * @return string The redirect URL
     */
    public function requestAuthorization()
    {
        $config = array(
            'oauth_options' => $this->dataStore->getConsumer(),
        );

        $this->client = new \Ornithology\Client\Twitter($config);

        $this->requestToken = $this->client->getRequestToken();

        return $this->client->getRedirectUrl();
    }

    /**
     * Authorize
     *
     * @param string $verifier The twitter OAuth pin code
     *
     * @return ZendOAuth\Token\Access The access token
     */
    public function authorize($verifier)
    {
        if ($this->client === null || $this->requestToken === null) {
            throw new \Exception('Client is not initialized');
        }

        $this->accessToken = $this->client->getAccessToken(
            array(
                'oauth_token' => $this->requestToken->getToken(),
                'oauth_verifier' => $verifier,
            ),
            $this->requestToken
        );

        $this->login($this->accessToken, $this->dataStore->getConsumer());

        $this->dataStore->setAccessToken($this->accessToken);

        return $this->accessToken;
    }

    /**
     * Login
     *
     * @param ZendOAuth\Token\Access $accessToken The access token
     * @param array                  $consumer    The consumer config array
     */
    public function login($accessToken, array $consumer)
    {
        $config = array(
            'access_token' => $accessToken,
            'oauth_options' => $consumer,
        );
        $this->client = new \Ornithology\Client\Twitter($config);

        $response = $this->client->account->verifyCredentials();

        if (!$response->isSuccess()) {
            throw new InvalidArgumentException('Failed to login to twitter with these credentials');
        } else {
            $this->loggedIn = true;
        }
    }

    /**
     * Tweet
     *
     * @param string $text The text we want to tweet
     */
    public function tweet($text)
    {
        $this->client->statuses->update($text);
    }

    /**
     * Get latest tweets
     *
     * @return array An array of tweets
     */
    public function getLatestTweets()
    {
        if ($this->loggedIn !== true) {
            throw new \Exception('You should log in first');
        }

        $options = array();
        $options['count'] = 200;

        $lastTweet = $this->dataStore->getLastTweetId();

        if ($lastTweet !== null) {
            $options['since_id'] = $lastTweet;
        }

        $tweets = array();
        while (!isset($statuses) || count($statuses) > ($options['count'] - 2)) {
            if (isset($oldestStatus)) {
                $options['max_id'] = $oldestStatus->id - 1;
            }

            $statuses = $this->client->statuses->homeTimeLine($options)->toValue();
            if (!isset($statuses->errors) || empty($statuses->errors)) {
                $tweets = array_merge($tweets, $statuses);
            }

            $oldestStatus = end($statuses);
            if (!isset($newestStatus) && !empty($statuses)) {
                if (!isset($statuses->errors) || empty($statuses->errors)) {
                    $newestStatus = reset($statuses);
                    $this->dataStore->setLastTweetId($newestStatus->id_str);
                }
            }
        }

        usort($tweets, function($a, $b) {
            return ($a->id > $b->id) ? 1 : -1;
        });

        $tweets = $this->dataStore->persistTweetList($tweets);

        return $tweets;
    }

    /**
     * Get a tweet by its internal id
     *
     * @param string $id The internal id
     *
     * @return stdClass The tweet object
     */
    public function getTweetById($id)
    {
        return $this->dataStore->getTweetByInternalId($id);
    }

    /**
     * Favorite a tweet
     *
     * @param string $id The internal id
     */
    public function favorite($id)
    {
        $tweet = $this->dataStore->getTweetByInternalId($id);
        if (empty($tweet)) {
            throw new InvalidArgumentException('This tweet does not exist');
        }

        $this->client->favoritesCreate($tweet->id_str);
    }

    /**
     * Retweet a tweet
     *
     * @param string $id The internal id
     */
    public function retweet($id)
    {
        $tweet = $this->dataStore->getTweetByInternalId($id);
        if (empty($tweet)) {
            throw new InvalidArgumentException('This tweet does not exist');
        }

        $this->client->retweet($tweet->id_str);
    }

    /**
     * Reply to a tweet
     *
     * @param string $id   The internal id
     * @param string $text The text we want to tweet
     */
    public function reply($id, $text)
    {
        $tweet = $this->dataStore->getTweetByInternalId($id);
        if (empty($tweet)) {
            throw new InvalidArgumentException('This tweet does not exist');
        }

        $text = '@' . $tweet->user->screen_name . ' ' . $text;

        $this->client->statuses->update($text, $tweet->id_str);
    }
}
