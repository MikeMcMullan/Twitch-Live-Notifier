<?php

namespace Twitch;

use Channel;

class InvalidChannels extends \Exception {}

class ChannelStatusUpdater {

    /**
     * @var TwitchUser
     */
    private $user;

    /**
     * @param TwitchUser $user
     */
    public function __construct(TwitchUser $user)
    {
        $this->user = $user;
    }

    /**
     * @param $username
     * @return array
     * @throws InvalidUserException
     */
    public function populateFollowing($username)
    {
        $following = $this->user->getFollowing($username);

        foreach($following as $channel)
        {
            Channel::firstOrCreate([
                'user' => $username,
                'name' => $channel['name']
            ]);
        }

        return array_fetch($following, 'name');
    }

    /**
     * @param $username
     * @return mixed
     * @throws InvalidChannels
     */
    public function refresh($username)
    {
        $channels = $this->getChannels($username);
        $liveChannels = array_fetch($this->user->getStreamingChannels($channels->toArray()), 'name');

        $isLive = $this->getLiveChannels($channels, $liveChannels);
        $offline= $this->getOfflineChannels($channels, $liveChannels);

        $this->updateOfflineChannels($offline);
        $this->updateLiveChannels($isLive);

        return $isLive;
    }

    /**
     * @param $channels
     * @param $liveChannels
     * @return array
     */
    private function getOfflineChannels($channels, array $liveChannels)
    {
        $channelNames = [];

        foreach($channels as $channel)
        {
            if ( ! in_array($channel->name, $liveChannels) && $channel->is_live != false)
            {
                $channelNames[] = $channel->name;
            }
        }

        return $channelNames;
    }

    /**
     * @param $channels
     * @param $liveChannels
     * @return array
     */
    private function getLiveChannels($channels, $liveChannels)
    {
        $channelNames = [];

        foreach($channels as $channel)
        {
            if (in_array($channel->name, $liveChannels) && $channel->is_live != true)
            {
                $channelNames[] = $channel->name;
            }
        }

        return $channelNames;
    }

    /**
     * @param $username
     * @throws InvalidChannels
     * @return array
     */
    private function getChannels($username)
    {
        $channels = Channel::where('user', '=', $username)->get();

        if ($channels->isEmpty())
        {
            throw new InvalidChannels('You are not following any channels or haven\'t run populateFollowing() yet');
        }

        return $channels;
    }

    /**
     * @param $channelNames
     * @internal param $channel_names
     * @return mixed
     */
    private function updateOfflineChannels($channelNames)
    {
        return ! empty($channelNames) && Channel::whereIn('name', $channelNames)->update(['is_live' => false]);
    }

    /**
     * @param $channelNames
     * @internal param $channel_names
     * @return mixed
     */
    private function updateLiveChannels($channelNames)
    {
        return ! empty($channelNames) &&Channel::whereIn('name', $channelNames)->update(['is_live' => true]);
    }
} 