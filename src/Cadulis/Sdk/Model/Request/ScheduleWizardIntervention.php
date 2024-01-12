<?php

namespace Cadulis\Sdk\Model\Request;

use Cadulis\Sdk\Model\Request\AbstractRequest;

class ScheduleWizardIntervention extends AbstractRequest
{
    const MODEL_IDENTIFIER = 'schedule_wizard_intervention';

    public $date_min;

    protected $_properties = ['date_min'];

    protected function checkContent(array $data = null)
    {
        $dateFields = ['date_min'];
        $this->checkDateFields($dateFields, $data);
    }
}