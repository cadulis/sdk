<?php

namespace Cadulis\Sdk\Model;

class ScheduledSlots extends AbstractCollection
{

    /**
     * @return ScheduledSlot
     */
    public function offsetGet($offset) : mixed
    {
        return parent::offsetGet($offset);
    }

    /**
     * @return ScheduledSlot
     */
    public function current() : mixed
    {
        return parent::current();
    }

    /**
     * @param mixed         $offset
     * @param ScheduledSlot $value
     */
    public function offsetSet($offset, $value) : void
    {
        if (!($value instanceof ScheduledSlot)) {
            throw new \Cadulis\Sdk\Exception('trying to set non valid element');
        }
        parent::offsetSet($offset, $value);
    }

    public function hydrate(array $data = [])
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
        $return = [];
        foreach ($this as $data) {
            $return[] = $data->toArray();
        }
        return $return;
    }

}