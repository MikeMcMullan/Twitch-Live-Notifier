<?php

namespace Twitch;

interface Notifier {

    public function send($to, $title, $body);

}