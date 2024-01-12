<?php

namespace Cadulis\Sdk\Model;

/**
 * @method User offsetGet($offset)
 * @method User current()
 */
class InterventionAssignments extends AbstractCollection
{

    public function offsetSet($offset, $value) : void
    {
        if (!($value instanceof User)) {
            throw new \Cadulis\Sdk\Exception('trying to set non valid element');
        }
        parent::offsetSet($offset, $value);
    }

    public function hydrate(array $data = [])
    {
        foreach ($data as $value) {
            if (!is_array($value)) {
                continue;
            }
            $assignment = new User();
            $assignment->hydrate($value);
            $this[] = $assignment;
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