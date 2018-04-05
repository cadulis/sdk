<?php

namespace Cadulis\Sdk\Model\Request;

class ScheduleWizard extends AbstractRequest
{
    const MODEL_IDENTIFIER = 'schedule_wizard';

    public $address;
    public $latitude;
    public $longitude;
    public $date_min;
    public $date_max;
    /**
     * @var string hh:mm:ss
     */
    public $duration;
    public $intervention_cref;

    /**
     * @var \Cadulis\Sdk\Model\InterventionType
     */
    public $intervention_type;

    protected $_properties = ['address', 'latitude', 'longitude', 'date_min', 'date_max', 'duration', 'intervention_cref'];

    /**
     * @return array
     */
    public function toArray()
    {
        $return = parent::toArray();
        if ($this->intervention_type !== null) {
            if (!($this->intervention_type instanceof \Cadulis\Sdk\Model\InterventionType)) {
                throw new \Cadulis\Sdk\Exception('invalid intervention_type instance (must be instanceof \Cadulis\Sdk\InterventionType)');
            }
            $return['intervention_type'] = $this->intervention_type->toArray();
        }

        return $return;
    }

    public function hydrate(array $data = [])
    {
        parent::hydrate($data);
        if (isset($data['intervention_type'])) {
            $this->intervention_type = new \Cadulis\Sdk\Model\InterventionType;
            $this->intervention_type->hydrate($data['intervention_type']);
        }
    }


    protected function checkContent(array $data = null)
    {
        $dateFields = ['date_min', 'date_max'];
        $this->checkDateFields($dateFields, $data);
        if ($this->duration !== null && !preg_match('#^[0-9]{2}\:[0-9]{2}\:[0-9]{2}$#', $this->duration)) {
            throw new \Cadulis\Sdk\Exception('invalid duration : ' . $this->duration);
        }
    }

    public function setDateMin(\DateTime $date)
    {
        $this->date_min = $date->format('c');
    }

    public function setDateMax(\DateTime $date)
    {
        $this->date_max = $date->format('c');
    }

}