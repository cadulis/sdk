<?php

namespace Cadulis\Sdk\Model;

class Interventions extends AbstractCollection
{

    /**
     * @return Intervention
     */
    public function offsetGet($offset) : mixed
    {
        return parent::offsetGet($offset);
    }

    /**
     * @return Intervention
     */
    public function current() : mixed
    {
        return parent::current();
    }

    /**
     * @param mixed        $offset
     * @param Intervention $value
     */
    public function offsetSet($offset, $value) : void
    {
        if (!($value instanceof Intervention)) {
            throw new \Cadulis\Sdk\Exception('trying to set non valid element');
        }
        parent::offsetSet($offset, $value);
    }

    public function hydrate(array $data = [])
    {
        foreach ($data as $value) {
            $intervention = new Intervention();
            $intervention->hydrate($value);
            $this[] = $intervention;
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