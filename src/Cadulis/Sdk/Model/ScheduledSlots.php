<?php

namespace Cadulis\Sdk\Model;

class ScheduledSlots extends AbstractCollection
{

    /**
     * @return ScheduledSlot
     */
    public function offsetGet($offset)
    {
        return parent::offsetGet($offset);
    }

    /**
     * @return ScheduledSlot
     */
    public function current()
    {
        return parent::current();
    }

    /**
     * @param mixed $offset
     * @param ScheduledSlot $value
     */
    public function offsetSet($offset, $value)
    {
        if (!($value instanceof ScheduledSlot)) {
            throw new \Cadulis\Sdk\Exception('trying to set non valid element');
        }
        parent::offsetSet($offset, $value);
    }

    public function hydrate(array $data = array())
    {
        foreach ($data as $value) {
            $scheduledSlot = new ScheduledSlot();
            $scheduledSlot->hydrate($value);
            $this[] = $scheduledSlot;
        }
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $return = array();
        foreach ($this as $data) {
            $return[] = $data->toArray();
        }
        return $return;
    }

}