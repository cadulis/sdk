<?php

namespace Cadulis\Sdk\Model;

class InterventionType extends AbstractModel
{

    public $id;
    public $name;
    protected $_properties = ['id', 'name'];

    protected function checkContent(array $data = null)
    {
        if ($data !== null && !isset($data['id'])) {
            throw new \Cadulis\Sdk\Exception('Required intervention_type.id');
        }
        if ($data === null && $this->id === null) {
            throw new \Cadulis\Sdk\Exception('Required intervention_type.id');
        }
    }
}