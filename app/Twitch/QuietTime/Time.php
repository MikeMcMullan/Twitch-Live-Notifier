<?php

namespace Twitch\QuietTime;

use Carbon\Carbon;

class Time {

    /**
     * @var Carbon
     */
    private $startTime;

    /**
     * @var int
     */
    private $hours;

    /**
     * @var int
     */
    private $minutes;

    /**
     * @param $startHour    int     The start hour of the period.
     * @param $startMinute  int     The start minute of the period.
     * @param $hours        int     How many hours should the period last for.
     * @param int $minutes  int     How many minutes upon that hour should the period last for.
     */
    public function __construct($startHour, $startMinute, $hours, $minutes = 0)
    {
        $this->hours = $hours;
        $this->minutes = $minutes;

        $this->startTime = Carbon::create(null, null, null, $startHour, $startMinute);
    }

    /**
     * Get Start Time.
     */
    public function getStartTime()
    {
        return $this->startTime;
    }

    /**
     * Get End Time
     */
    public function getEndTime()
    {
        return $this->startTime->addHours($this->hours)->addMinutes($this->minutes);
    }
}