<?php

use Twitch\ChannelStatusUpdater;
use Twitch\TwitchUser;

class HomeController extends BaseController {

    /**
     * @var TwitchSDK
     */
    private $twitch;
    /**
     * @var TwitchService
     */
    private $service;

    /**
     * @param TwitchUser $twitch
     * @param ChannelStatusUpdater $service
     */
    public function __construct(TwitchUser $twitch, ChannelStatusUpdater $service)
    {
        $this->twitch = $twitch;
        $this->channel = $service;
    }

    /**
     *
     */
    public function channels()
	{
//        $this->service->populateFollowing('McsMike');
//        exit;

//        var_dump($this->twitch->getFollowing('McsMike'));

        $liveChannels = $this->channel->refresh('McsMike');

        if ( ! empty($liveChannels))
        {
            $push = App::make('PushBullet');
            $push->pushNote('', 'Twitch broadcasters went live', implode(', ', $liveChannels));
        }
	}

}
