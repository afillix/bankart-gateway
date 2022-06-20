<?php

namespace App\Payments\src\Transaction\Base;

use App\Payments\src\Schedule\ScheduleData;

/**
 * Trait ScheduleTrait
 *
 * @package App\Payments\src\Transaction\Base
 */
trait ScheduleTrait {

    /**
     * @var ScheduleData
     */
    protected $schedule;

    /**
     * @return ScheduleData|null
     */
    public function getSchedule() {
        return $this->schedule;
    }

    /**
     * @param ScheduleData|null $schedule
     *
     * @return $this
     */
    public function setSchedule(ScheduleData $schedule = null) {
        $this->schedule = $schedule;

        return $this;
    }

}
