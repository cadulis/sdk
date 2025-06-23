<?php

namespace Cadulis\Sdk\Model\Request;

class Interventions extends AbstractRequest
{
    const MODEL_IDENTIFIER = 'intervention.search';

    /**
     * @var mixed : string or array of strings
     */
    public $cref;

    /**
     * @var mixed : string or array of strings
     */
    public $reference;
    public $light_model = true;

    /**
     * @var \Cadulis\Sdk\Model\Request\Pagination
     */
    public $pagination;

    /**
     * @var \Cadulis\Sdk\Model\InterventionTypes
     */
    public $intervention_types;

    /**
     * @var string date (ISO 8601) eg : 2004-02-12T15:19:21+00:00
     */
    public $scheduled_start_at_min;

    /**
     * @var string date (ISO 8601) eg : 2004-02-12T15:19:21+00:00
     */
    public $scheduled_start_at_max;

    /**
     * @var bool
     */
    public $with_appointment;

    public $without_scheduled_start;
    public $without_appointment;
    public $parent_id;

    /**
     * @var string @see \Cadulis\Sdk\Model\Intervention::STATUS_ ...
     */
    public $status;

    protected $_properties = [
        'cref',
        'light_model',
        'reference',
        'scheduled_start_at_min',
        'scheduled_start_at_max',
        'with_appointment',
        'status',
        'intervention_types',
        'without_scheduled_start',
        'without_appointment',
        'parent_id',
    ];

    public function __construct(array $data = null)
    {
        $this->pagination = new Pagination;
        parent::__construct($data);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $return = parent::toArray();
        if (!empty($this->intervention_types)) {
            if (!($this->intervention_types instanceof \Cadulis\Sdk\Model\InterventionTypes)) {
                throw new \Cadulis\Sdk\Exception(
                    'invalid intervention_types instance (must be instanceof \Cadulis\Sdk\Model\InterventionTypes'
                );
            }
            $return['intervention_types'] = $this->intervention_types->toArray();
        }
        $return['pagination'] = $this->pagination->toArray();

        return $return;
    }

    public function hydrate(array $data = [])
    {
        parent::hydrate($data);
        if (isset($data['intervention_types'])) {
            $this->intervention_types = new \Cadulis\Sdk\Model\InterventionTypes();
            if (!is_array($data['intervention_types'])) {
                $data['intervention_types'] = [$data['intervention_types']];
            }
            foreach ($data['intervention_types'] as $k => $interventionTypeData) {
                if (is_scalar($interventionTypeData)) {
                    $data['intervention_types'][$k] = ['id' => $interventionTypeData];
                }
            }
            $this->intervention_types->hydrate($data['intervention_types']);
        }
        if (isset($data['pagination'])) {
            if (!is_array($data['pagination'])) {
                throw new \Exception('Invalid parameter "pagination"');
            }
            $this->pagination->hydrate($data['pagination']);
        } else {
            if (isset($data['page'])) {
                $this->pagination->page = $data['page'];
            }
            if (isset($data['limit'])) {
                $this->pagination->limit = $data['limit'];
            }
        }
        if ($this->with_appointment === 'true') {
            $this->with_appointment = 1;
        }
        if ($this->with_appointment === 'false') {
            $this->with_appointment = 0;
        }
        if ($this->without_appointment === 'true') {
            $this->without_appointment = 1;
        }
        if ($this->without_appointment === 'false') {
            $this->without_appointment = 0;
        }
        if ($this->light_model === 'true') {
            $this->light_model = 1;
        }
        if ($this->light_model === 'false') {
            $this->light_model = 0;
        }
    }

    protected function checkContent(array $data = null)
    {
        $dateFields = ['scheduled_start_at_min', 'scheduled_start_at_max'];
        $this->checkDateFields($dateFields, $data);

        $stringOrStringArrays = ['cref', 'reference', 'status'];
        foreach ($stringOrStringArrays as $stringOrStringArray) {
            if (isset($data[$stringOrStringArray]) && is_array($data[$stringOrStringArray])) {
                foreach ($data[$stringOrStringArray] as $val) {
                    if (!is_string($val)) {
                        throw new \Cadulis\Sdk\Exception($stringOrStringArray . ' must be string typed');
                    }
                }
            }
            if ($this->$stringOrStringArray !== null && is_array($this->$stringOrStringArray)) {
                foreach ($this->$stringOrStringArray as $val) {
                    if (!is_string($val)) {
                        throw new \Cadulis\Sdk\Exception($stringOrStringArray . ' must be string typed');
                    }
                }
            }
        }

    }

}