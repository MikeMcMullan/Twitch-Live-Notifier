<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Twitch\ChannelStatusUpdater;
use Twitch\Notifier;

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
     * @var Notifier
     */
    private $notifier;

    /**
     * Create a new command instance.
     *
     * @param ChannelStatusUpdater $channel
     * @param Notifier $notifier
     * @return \twitchRefreshFollowingStatus
     */
	public function __construct(ChannelStatusUpdater $channel, Notifier $notifier)
	{
		parent::__construct();
        $this->channel = $channel;
        $this->notifier = $notifier;
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
            $title = 'Twitch broadcasters went live';
            $items[] = implode(', ', array_fetch($liveChannels, 'channel.display_name'));
            $items[] = '-------------';

            foreach ($liveChannels as $channel)
            {
                $items[] = $channel['channel']['display_name'] . ': ' . $channel['game'];
            }

            $response = $this->notifier->send('', $title, implode("\r\n", $items));

            Notification::create([ 'iden' => $response->iden ]);

            $this->info('Notifications sent.');
        }
        else
        {
            $this->info('No notifications sent.');
        }
	}
}