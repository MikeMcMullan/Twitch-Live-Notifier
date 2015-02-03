<?php

namespace Twitch\QuietTime;

use Carbon\Carbon;

class QuietTime {

    /**
     * @var array
     */
    private $periods = [];

    /**
     * @var
     */
    private $now;

    /**
     * @param array $timePeriods
     * @param Carbon $now
     */
    public function __construct($timePeriods = [], Carbon $now = null)
    {
        foreach ($timePeriods as $period)
        {
            $this->add($period);
        }

        if ($now == null)
        {
            $this->setNow(Carbon::now());
        }
    }

    /**
     * @param $now
     * @return $this
     */
    public function setNow(Carbon $now)
    {
        $this->now = $now;

        return $this;
    }

    /**
     * @param Time $period
     * @return $this
     */
    public function add(Time $period)
    {
        $this->periods[] = $period;

        return $this;
    }

    /**
     * @return bool
     */
    public function active()
    {
        foreach ($this->periods as $period)
        {
            if ($this->now >= $period->getStartTime() && $this->now <= $period->getEndTime())
            {
                return true;
            }
        }

        return false;
    }

}