<?php

namespace Cadulis\Sdk\Model;

abstract class AbstractCollection extends AbstractModel implements \ArrayAccess, \Countable, \Iterator
{

    /**
     * @var array
     */
    protected $_data = [];

    /**
     * @var int Iterator implementation
     */
    protected $_iteratorPosition = 0;

    //#####     ArrayAccess     #####//

    public function offsetExists($offset) : bool
    {
        return isset($this->_data[$offset]);
    }

    public function offsetGet($offset) : mixed
    {
        if (!isset($this->_data[$offset])) {
            return null;
        }

        return $this->_data[$offset];
    }

    public function offsetSet($offset, $value) : void
    {
        if ($offset === null) {
            $this->_data[] = $value;
        } else {
            $this->_data[$offset] = $value;
        }
    }

    public function offsetUnset($offset) : void
    {
        if (isset($this->_data[$offset])) {
            $keys = array_keys($this->_data);
            if ($this->_iteratorPosition > array_search($offset, $keys)) {
                $this->_iteratorPosition--;
            }
            unset($this->_data[$offset]);
        }
    }

    //#####     Countable     #####//

    public function count() : int
    {
        return count($this->_data);
    }

    //#####     Iterator     #####//

    public function rewind() : void
    {
        $this->_iteratorPosition = 0;
    }

    public function current() : mixed
    {
        $keys = array_keys($this->_data);

        return $this->_data[$keys[$this->_iteratorPosition]];
    }

    public function key() : mixed
    {
        $keys = array_keys($this->_data);

        return $keys[$this->_iteratorPosition];
    }

    public function next() : void
    {
        ++$this->_iteratorPosition;
    }

    public function valid() : bool
    {
        $keys = array_keys($this->_data);

        return array_key_exists($this->_iteratorPosition, $keys)
            && array_key_exists(
                $keys[$this->_iteratorPosition],
                $this->_data
            );
    }

    //#####          #####//

    public function hydrate(array $data = [])
    {
        foreach ($data as $key => $value) {
            $this[$key] = $value;
        }
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $return = [];
        foreach ($this as $key => $value) {
            $return[$key] = $value;
        }

        return $return;
    }


}