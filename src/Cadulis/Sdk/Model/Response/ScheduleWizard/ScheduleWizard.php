<?php

namespace Cadulis\Sdk\Model\Response\ScheduleWizard;

class ScheduleWizard extends \Cadulis\Sdk\Model\AbstractCollection
{

    /**
     * @return ScheduleWizardDate
     */
    public function offsetGet($offset) : mixed
    {
        return parent::offsetGet($offset);
    }

    /**
     * @return ScheduleWizardDate
     */
    public function current() : mixed
    {
        return parent::current();
    }

    public function offsetSet($offset, $value) : void
    {
        if (!($value instanceof ScheduleWizardDate)) {
            throw new \Cadulis\Sdk\Exception('trying to set non valid element');
        }
        parent::offsetSet($offset, $value);
    }

    public function hydrate(array $data = [])
    {
        foreach ($data['dates'] as $value) {
            $date = new ScheduleWizardDate();
            $date->hydrate($value);
            $this[] = $date;
        }
    }

    /**
     * @return array
     */
    public function toArray(bool $simpleData = false)
    {
        $return = [
            'dates' => [],
        ];
        foreach ($this as $data) {
            $return['dates'][] = $data->toArray($simpleData);
        }

        return $return;
    }

    public function getBestSlot()
    {
        $bestSlot = null;
        $relevance = 0;
        foreach ($this as $date) {
            foreach ($date as $slot) {
                if ($slot->available && $slot->relevance > $relevance) {
                    $bestSlot = $slot;
                    $relevance = $slot->relevance;
                }
            }
        }

        return $bestSlot;
    }
}