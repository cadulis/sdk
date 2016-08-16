<?php

namespace Cadulis\Sdk\Service;

class ScheduleWizard extends AbstractService
{
    public function newWizardInput()
    {
        return new \Cadulis\Sdk\Model\Request\ScheduleWizard();
    }

    public function getSlots(\Cadulis\Sdk\Model\Request\ScheduleWizard $scheduleWizardInput)
    {
        if ($scheduleWizardInput->address === null) {
            throw new \Cadulis\Sdk\Exception('Required address for schedule wizard request');
        }
        $result = $this->_callClient(\Cadulis\Sdk\Model\Routes\Route::IDENTIFIER_SCHEDULE_WIZARD, $scheduleWizardInput);
        if (!is_array($result) || !isset($result['result'])) {
            throw new \Cadulis\Sdk\Exception('Invalid schedule wizard response');
        }

        return new \Cadulis\Sdk\Model\Response\ScheduleWizard\ScheduleWizard($result['result']);

    }

}