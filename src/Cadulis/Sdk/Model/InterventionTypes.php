<?php

namespace Cadulis\Sdk\Model;

class InterventionTypes extends AbstractCollection
{

    /**
     * @return InterventionType
     */
    public function offsetGet($offset) : mixed
    {
        return parent::offsetGet($offset);
    }

    /**
     * @return InterventionType
     */
    public function current() : mixed
    {
        return parent::current();
    }

    /**
     * @param mixed            $offset
     * @param InterventionType $value
     */
    public function offsetSet($offset, $value) : void
    {
        if (!($value instanceof InterventionType)) {
            throw new \Cadulis\Sdk\Exception('trying to set non valid element');
        }
        parent::offsetSet($offset, $value);
    }

    public function hydrate(array $data = [])
    {
        foreach ($data as $value) {
            $interventionType = new InterventionType();
            $interventionType->hydrate($value);
            $this[] = $interventionType;
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