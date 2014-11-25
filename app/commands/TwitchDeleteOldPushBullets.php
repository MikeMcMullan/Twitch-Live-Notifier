<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class TwitchDeleteOldPushBullets extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'twitch:deleteOldPushBullets';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Delete old push bullets';

	/**
	 * @var TwitchSDKAdapter
	 */
	private $sdk;

	/**
	 * Create a new command instance.
	 *
	 * @param PushBullet $sdk
	 */
	public function __construct(PushBullet $sdk)
	{
		parent::__construct();
		$this->sdk = $sdk;
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$notifications = Notification::all();

		if ( ! $notifications->isEmpty())
		{
			$notifications->each(function($notification)
			{
				$this->sdk->deletePush($notification->iden);
				$this->info('Push ' . $notification->iden . ' was deleted.');
			});

			$notifications->first()->truncate();
		}
	}
}
