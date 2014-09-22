<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Twitch\ChannelStatusUpdater;

class TwitchPopulateFollowing extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'twitch:populateFollowing';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Update the list of channels a user is following.';
    /**
     * @var ChannelStatusUpdater
     */
    private $channel;

    /**
     * Create a new command instance.
     *
     * @param ChannelStatusUpdater $channel
     * @return \twitchPopulateFollowing
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
		$this->channel->populateFollowing('McsMike');

        $this->info('Done.');
	}
}
