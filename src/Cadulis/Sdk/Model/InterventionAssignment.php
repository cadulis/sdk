<?php

namespace Cadulis\Sdk\Model;

class InterventionAssignment extends AbstractModel
{

    /**
     * @var string date (ISO 8601) eg : 2004-02-12T15:19:21+00:00
     */
    public $scheduled_start_at;

    /**
     * @var InterventionAssignments
     */
    public $assignments;

    protected $_properties = [
        'scheduled_start_at',
    ];


    /**
     * @return array
     */
    public function toArray()
    {
        $return = parent::toArray();
        if (!empty($this->assignments)) {
            if (!($this->assignments instanceof InterventionAssignments)) {
                throw new \Cadulis\Sdk\Exception(
                    'invalid assignments instance (must be instanceof \Cadulis\Sdk\InterventionAssignments)'
                );
            }
            $return['assignments'] = $this->assignments->toArray();
        }
        return $return;
    }

    public function hydrate(array $data = [])
    {
        parent::hydrate($data);

        if (isset($data['assignments'])) {
            $this->assignments = new InterventionAssignments;
            if (!is_array($data['assignments'])) {
                throw new \Exception('Invalid parameter "assignments"');
            }
            $this->assignments->hydrate($data['assignments']);
        }
    }

    protected function checkContent(array $data = null)
    {
        $dateFields = ['scheduled_start_at'];
        $this->checkDateFields($dateFields, $data);
    }
}