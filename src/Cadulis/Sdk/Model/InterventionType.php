<?php

namespace Cadulis\Sdk\Model;

class InterventionType extends AbstractModel
{

    public    $id          = null;
    public    $reference   = null;
    public    $name;
    protected $_properties = ['id', 'reference', 'name'];

    protected function checkContent(array $data = null)
    {
        if ($data !== null && empty($data['id']) && empty($data['reference'])) {
            throw new \Cadulis\Sdk\Exception('Required intervention_type.id or intervention_type.reference');
        }
    }
}