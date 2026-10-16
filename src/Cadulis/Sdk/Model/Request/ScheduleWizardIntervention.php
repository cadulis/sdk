<?php

namespace Cadulis\Sdk\Model\Request;

use Cadulis\Sdk\Model\Request\AbstractRequest;

class ScheduleWizardIntervention extends AbstractRequest
{
    const MODEL_IDENTIFIER = 'schedule_wizard_intervention';

    public $date_min;
    public $users_count;
    public $weeks;
    public $start_at_first_slot;

    protected $_properties = ['date_min', 'users_count', 'weeks', 'start_at_first_slot'];

    protected function checkContent(array $data = null)
    {
        $dateFields = ['date_min'];
        $this->checkDateFields($dateFields, $data);
    }
}