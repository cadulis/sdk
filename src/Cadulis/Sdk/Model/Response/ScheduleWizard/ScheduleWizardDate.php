<?php

namespace Cadulis\Sdk\Model\Response\ScheduleWizard;

class ScheduleWizardDate extends \Cadulis\Sdk\Model\AbstractCollection
{
    public    $date;
    protected $_properties = ['date'];

    /**
     * @return ScheduleWizardSlot
     */
    public function offsetGet($offset) : mixed
    {
        return parent::offsetGet($offset);
    }

    /**
     * @return ScheduleWizardSlot
     */
    public function current() : mixed
    {
        return parent::current();
    }

    public function offsetSet($offset, $value) : void
    {
        if (!($value instanceof ScheduleWizardSlot)) {
            throw new \Cadulis\Sdk\Exception('trying to set non valid element');
        }
        parent::offsetSet($offset, $value);
    }

    public function hydrate(array $data = [])
    {
        if (isset($data['date'])) {
            $this->date = $data['date'];
        }
        foreach ($data['slots'] as $value) {
            $slot = new ScheduleWizardSlot();
            $slot->hydrate($value);
            $this[] = $slot;
        }
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $return = [
            'slots' => [],
        ];
        if ($this->date !== null) {
            $return['date'] = $this->date;
        }
        foreach ($this as $data) {
            $return['slots'][] = $data->toArray();
        }
        return $return;
    }

    protected function checkContent(array $data = null)
    {
        $dateFields = ['date'];
        $this->checkDateFields($dateFields, $data);
    }

}