<?php

namespace Twitch\Notifiers;

use Twitch\Notifier;

class PushBullet implements Notifier {

    /**
     * @var PushBullet
     */
    private $bullet;

    /**
     * @param \PushBullet $bullet
     */
    public function __construct(\PushBullet $bullet)
    {
        $this->bullet = $bullet;
    }

    /**
     * @param $to
     * @param $title
     * @param $body
     */
    public function send($to, $title, $body)
    {
        return $this->bullet->pushNote($to, $title, $body);
    }
}