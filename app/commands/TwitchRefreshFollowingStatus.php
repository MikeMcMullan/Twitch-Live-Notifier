<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Twitch\ChannelStatusUpdater;

class TwitchRefreshFollowingStatus extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'twitch:refreshFollowingStatus';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Check to see which channels have gone live or offline.';
    /**
     * @var ChannelStatusUpdater
     */
    private $channel;

    /**
     * Create a new command instance.
     *
     * @param ChannelStatusUpdater $channel
     * @return \twitchRefreshFollowingStatus
     */
	public function __construct(ChannelStatusUpdater $channel)
	{
		parent::__construct();
        $this->channel = $channel;
    }

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
        $liveChannels = $this->channel->refresh('McsMike');

        if ( ! empty($liveChannels))
        {
            $push = App::make('PushBullet');
            $push->pushNote('', 'Twitch broadcasters went live', implode(', ', $liveChannels));
            $this->info('Notifications sent.');
        }
        else
        {
            $this->info('No notifications sent.');
        }
	}
}