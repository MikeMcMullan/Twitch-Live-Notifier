<?php

namespace Twitch;

use Channel;
use Illuminate\Support\Collection;
use ritero\SDK\TwitchTV\TwitchSDK;

class InvalidUserException extends \Exception {}

class TwitchUser {

    /**
     * @var TwitchSDK
     */
    private $twitchSdk;

    public function __construct(TwitchSDK $twitchSdk)
    {
        $this->twitchSdk = $twitchSdk;
    }

    private function convertToCollection($object)
    {
        return new Collection(json_decode(json_encode($object), true));
    }

    public function getFollowing($username)
    {
        $response = $this->twitchSdk->userFollowChannels($username, 9999);

        if (isset($response->error))
        {
            throw new InvalidUserException($response->message);
        }

        $channel = $this->convertToCollection($response->follows);

        return $this->addFollowingToDB($username, $channel->lists('channel'));
    }

    private function addFollowingToDB($username, $channels)
    {
        foreach($channels as $channel)
        {
            Channel::firstOrCreate([
                'user' => $username,
                'name' => $channel['name']
            ]);
        }

        return $channels;
    }

    public function getStreamingChannels($channels)
    {
        $channels = array_fetch($channels, 'name');
        $response = $this->twitchSdk->getStreams(null, null, null, implode(',', $channels))->streams;
        $streams  = $this->convertToCollection($response);

        return $streams->lists('channel');
    }
} 