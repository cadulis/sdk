<?php

namespace Cadulis\Sdk\Service;

class ScheduleWizard extends AbstractService
{
    /**
     * @deprecated only feeds getSlots()
     */
    public function newWizardInput()
    {
        return new \Cadulis\Sdk\Model\Request\ScheduleWizard();
    }

    /**
     * @deprecated the Cadulis API does not serve slots for an intervention not created yet:
     * the call throws "Forbidden route". Create the intervention, then read its slots
     * through the API route interventions/{id}/schedule-wizard.
     */
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