<?php

namespace Cadulis\Sdk\Model;

class Customers extends AbstractCollection
{

    /**
     * @return Customer
     */
    public function offsetGet($offset) : mixed
    {
        return parent::offsetGet($offset);
    }

    /**
     * @return Customer
     */
    public function current() : mixed
    {
        return parent::current();
    }

    /**
     * @param mixed    $offset
     * @param Customer $value
     */
    public function offsetSet($offset, $value) : void
    {
        if (!($value instanceof Customer)) {
            throw new \Cadulis\Sdk\Exception('trying to set non valid element');
        }
        parent::offsetSet($offset, $value);
    }

    public function hydrate(array $data = [])
    {
        foreach ($data as $value) {
            $customer = new Customer();
            $customer->hydrate($value);
            $this[] = $customer;
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