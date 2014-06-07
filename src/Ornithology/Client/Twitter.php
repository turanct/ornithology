<?php

namespace Ornithology\Client;

class Twitter extends \ZendService\Twitter\Twitter
{
    public function retweet($tweetId)
    {
        return $this->post('statuses/retweet/' . $tweetId);
    }
}
