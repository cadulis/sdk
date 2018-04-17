<?php

namespace Cadulis\Sdk\Model;

class ScheduledSlot extends AbstractModel
{
    /**
     * @var int duration in seconds
     */
    public $duration;

    /**
     * @var string date (ISO 8601) eg : 2004-02-12T15:19:21+00:00
     */
    public $scheduled_start_at;

    protected $_properties = ['duration', 'scheduled_start_at'];

    protected function checkContent(array $data = null)
    {
        $dateFields = ['scheduled_start_at'];
        $this->checkDateFields($dateFields, $data);
    }

    public function setScheduledStart(\DateTime $date)
    {
        $this->scheduled_start_at = $date->format('c');
    }
}
