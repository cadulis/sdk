<?php

namespace Cadulis\Sdk\Model\Response;

class Customers extends \Cadulis\Sdk\Model\Response\AbstractResponse
{

    protected $_properties = [];

    /**
     * @var \Cadulis\Sdk\Model\Customers
     */
    public $customers;

    /**
     * @var \Cadulis\Sdk\Model\Response\Pagination
     */
    public $pagination;

    /**
     * @var \Cadulis\Sdk\Model\Request\Customers
     */
    public $search;

    public function __construct(array $data = null)
    {
        $this->customers = new \Cadulis\Sdk\Model\Customers();
        $this->pagination = new \Cadulis\Sdk\Model\Response\Pagination();
        parent::__construct($data);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $return = parent::toArray();
        if (!empty($this->customers)) {
            if (!($this->customers instanceof \Cadulis\Sdk\Model\Customers)) {
                throw new \Cadulis\Sdk\Exception(
                    'invalid customers instance (must be instanceof \Cadulis\Sdk\Model\Customers'
                );
            }
            $return['customers'] = $this->customers->toArray();
        }
        if (!empty($this->pagination)) {
            if (!($this->pagination instanceof \Cadulis\Sdk\Model\Response\Pagination)) {
                throw new \Cadulis\Sdk\Exception(
                    'invalid pagination instance (must be instanceof \Cadulis\Sdk\Model\Response\Pagination'
                );
            }
            $return['pagination'] = $this->pagination->toArray();
        }
        if (!empty($this->search)) {
            if (!($this->search instanceof \Cadulis\Sdk\Model\Request\Customers)) {
                throw new \Cadulis\Sdk\Exception(
                    'invalid search instance (must be instanceof \Cadulis\Sdk\Model\Request\Customers'
                );
            }
            $return['search'] = $this->search->toArray();
        }

        return $return;
    }

    public function hydrate(array $data = [])
    {
        parent::hydrate($data);
        if (isset($data['customers'])) {
            $this->customers = new \Cadulis\Sdk\Model\Customers();
            if (!is_array($data['customers'])) {
                throw new \Exception('Invalid parameter "customers"');
            }
            $this->customers->hydrate($data['customers']);
        }
        if (isset($data['pagination'])) {
            $this->pagination = new \Cadulis\Sdk\Model\Response\Pagination();
            if (!is_array($data['pagination'])) {
                throw new \Exception('Invalid parameter "pagination"');
            }
            $this->pagination->hydrate($data['pagination']);
        }
        if (isset($data['search'])) {
            $this->search = new \Cadulis\Sdk\Model\Request\Customers();
            if (!is_array($data['search'])) {
                throw new \Exception('Invalid parameter "search"');
            }
            $this->search->hydrate($data['search']);
        }
    }


}