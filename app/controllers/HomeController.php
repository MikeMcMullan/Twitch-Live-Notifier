<?php

use Twitch\ChannelStatusUpdater;

class HomeController extends BaseController {

    /**
     * @var TwitchService
     */
    private $service;

    /**
     * @param ChannelStatusUpdater $service
     */
    public function __construct(ChannelStatusUpdater $service)
    {
        $this->channel = $service;
    }

    /**
     *
     */
    public function channels()
	{
//        var_dump($this->channel->populateFollowing('McsMike'));
//        var_dump($this->channel->refresh('McsMike'));

//        $this->service->populateFollowing('McsMike');
//        exit;

//        var_dump($this->twitch->getFollowing('McsMike'));

//        $liveChannels = $this->channel->refresh('McsMike');
//
//        if ( ! empty($liveChannels))
//        {
//            $push = App::make('PushBullet');
//            $push->pushNote('', 'Twitch broadcasters went live', implode(', ', $liveChannels));
//        }
	}

}
