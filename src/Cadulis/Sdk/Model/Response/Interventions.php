<?php

namespace Cadulis\Sdk\Model\Response;

class Interventions extends \Cadulis\Sdk\Model\Response\AbstractResponse
{

    protected $_properties = [];

    /**
     * @var \Cadulis\Sdk\Model\Interventions
     */
    public $interventions;

    /**
     * @var \Cadulis\Sdk\Model\Response\Pagination
     */
    public $pagination;

    /**
     * @var \Cadulis\Sdk\Model\Request\Interventions
     */
    public $search;

    public function __construct(array $data = null)
    {
        $this->interventions = new \Cadulis\Sdk\Model\Interventions();
        $this->pagination = new \Cadulis\Sdk\Model\Response\Pagination();
        parent::__construct($data);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $return = parent::toArray();
        if (!empty($this->interventions)) {
            if (!($this->interventions instanceof \Cadulis\Sdk\Model\Interventions)) {
                throw new \Cadulis\Sdk\Exception('invalid interventions instance (must be instanceof \Cadulis\Sdk\Model\Interventions');
            }
            $return['interventions'] = $this->interventions->toArray();
        }
        if (!empty($this->pagination)) {
            if (!($this->pagination instanceof \Cadulis\Sdk\Model\Response\Pagination)) {
                throw new \Cadulis\Sdk\Exception('invalid pagination instance (must be instanceof \Cadulis\Sdk\Model\Response\Pagination');
            }
            $return['pagination'] = $this->pagination->toArray();
        }
        if (!empty($this->search)) {
            if (!($this->search instanceof \Cadulis\Sdk\Model\Request\Interventions)) {
                throw new \Cadulis\Sdk\Exception('invalid search instance (must be instanceof \Cadulis\Sdk\Model\Request\Interventions');
            }
            $return['search'] = $this->search->toArray();
        }

        return $return;
    }

    public function hydrate(array $data = [])
    {
        parent::hydrate($data);
        if (isset($data['interventions'])) {
            $this->interventions = new \Cadulis\Sdk\Model\Interventions();
            if (!is_array($data['interventions'])) {
                throw new \Exception('Invalid parameter "interventions"');
            }
            $this->interventions->hydrate($data['interventions']);
        }
        if (isset($data['pagination'])) {
            $this->pagination = new \Cadulis\Sdk\Model\Response\Pagination();
            if (!is_array($data['pagination'])) {
                throw new \Exception('Invalid parameter "pagination"');
            }
            $this->pagination->hydrate($data['pagination']);
        }
        if (isset($data['search'])) {
            $this->search = new \Cadulis\Sdk\Model\Request\Interventions();
            if (!is_array($data['search'])) {
                throw new \Exception('Invalid parameter "search"');
            }
            $this->search->hydrate($data['search']);
        }
    }


}