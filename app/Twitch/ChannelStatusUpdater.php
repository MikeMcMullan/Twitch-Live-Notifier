<?php

namespace Twitch;

use Channel;
use ritero\SDK\TwitchTV\TwitchSDK;

class InvalidChannels extends \Exception {}
class InvalidUserException extends \Exception {}

class ChannelStatusUpdater {

    /**
     * @var TwitchSDK
     */
    private $sdk;

    /**
     * @param TwitchSDKAdapter $sdk
     */
    public function __construct(TwitchSDKAdapter $sdk)
    {
        $this->sdk = $sdk;
    }

    /**
     * @param $username
     * @return array
     * @throws InvalidUserException
     */
    public function populateFollowing($username)
    {
        $following = $this->sdk->userFollowChannels($username, 100);

        if (array_get($following, 'error'))
        {
            throw new InvalidUserException($following['message']);
        }

        $this->addFollowed($following['follows'], $username);
        $this->removeNoLongerFollowedPeople($following['follows'], $username);

        return $following;
    }

    private function removeNoLongerFollowedPeople(array $follows, $username)
    {
        $currentlyFollowing = Channel::where('user', $username)->lists('name');
        $noLongerFollowed = array_diff($currentlyFollowing, array_fetch($follows, 'channel.name'));

        Channel::whereIn('name', $noLongerFollowed)->delete();
    }

    private function addFollowed(array $follows, $username)
    {
        foreach ($follows as $channel)
        {
            Channel::firstOrCreate([
                'user'          => $username,
                'display_name'  => $channel['channel']['display_name'],
                'name'          => $channel['channel']['name']
            ]);
        }
    }

    /**
     * @param $username
     * @return mixed
     * @throws InvalidChannels
     */
    public function refresh($username)
    {
        $followedChannels = $this->getChannels($username);
        $liveChannels = $this->sdk->getStreams(null, null, null, implode(',', $followedChannels->lists('name')));

        $isLive = $this->getLiveChannels($followedChannels, $liveChannels);
        $offline= $this->getOfflineChannels($followedChannels, $liveChannels);

        $this->updateOfflineChannels($offline);
        $this->updateLiveChannels($isLive);

        return $isLive;
    }

    /**
     * @param $channels
     * @param $liveChannels
     * @return array
     */
    private function getOfflineChannels($channels, $liveChannels)
    {
        $channelNames = [];
        $liveChannelNames = $liveChannels->make($liveChannels['streams'])->fetch('channel.name');

        foreach($channels as $channel)
        {
            if ( ! in_array($channel->name, $liveChannelNames->toArray()) && $channel->is_live != false)
            {
                $channelNames[] = $channel->name;
            }
        }

        return $channelNames;
    }

    /**
     * @param $followedChannels
     * @param $liveChannels
     * @return array
     */
    private function getLiveChannels($followedChannels, $liveChannels)
    {
        $channels = [];
        $liveChannelNames = $liveChannels->make($liveChannels['streams']);

        foreach($followedChannels as $channel)
        {
            $liveChannelNames->first(function($key, $value) use(&$channels, $channel)
            {
                if ($channel->name === $value['channel']['name'] && $channel->is_live != true)
                {
                    $channels[] = $value;
                }
            });
        }

        return $channels;
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
            throw new InvalidChannels("'{$username}' is not following any channels or they have not been imported yet.");
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
     * @param $channels
     * @internal param $channel_names
     * @return mixed
     */
    private function updateLiveChannels($channels)
    {
        if ( ! empty($channels))
        {
            foreach($channels as $channel)
            {
                Channel::where('name', '=', $channel['channel']['name'])->update([
                    'is_live' => true,
                    'game'    => $channel['game']
                ]);
            }
        }
    }
}