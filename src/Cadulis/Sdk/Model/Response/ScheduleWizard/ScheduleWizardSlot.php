<?php

namespace Cadulis\Sdk\Model\Response\ScheduleWizard;

class ScheduleWizardSlot extends \Cadulis\Sdk\Model\Response\AbstractResponse
{
    public $date;
    public $time;
    public $magnetTime;
    public $available         = false;
    public $unavailableReason = '';
    public $userId;
    public $relevance;
    public $relevanceClass;

    protected $_properties       = [
        'date',
        'time',
        'magnetTime',
        'available',
        'unavailableReason',
        'userId',
        'relevance',
        'relevanceClass',
    ];
    protected $_propertiesSimple = [
        'date',
        'time',
        'userId',
        'relevanceClass',
    ];

    protected function checkContent(array $data = null)
    {
        $dateFields = ['date'];
        $this->checkDateFields($dateFields, $data);
    }

    /**
     * @return array
     */
    public function toArray(bool $simpleData = false)
    {
        if (!$simpleData) {
            return parent::toArray();
        }

        $this->checkContent();
        $return = [];
        foreach ($this->_propertiesSimple as $property) {
            if ($this->$property !== null) {
                $return[$property] = $this->$property;
            }
        }

        return $return;
    }
}