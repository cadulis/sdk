<?php

namespace Cadulis\Sdk\Model\Request;

class Customers extends AbstractRequest
{
    const MODEL_IDENTIFIER = 'customer.search';

    /**
     * @var mixed : string or array of strings
     */
    public $category = '';

    /**
     * @var string search query
     */
    public $search_query = '';

    /**
     * @var \Cadulis\Sdk\Model\Request\Pagination
     */
    public $pagination;

    /**
     * @var bool return all customers, default to false
     */
    public $with_inactive = false;

    protected $_properties = [
        'search_query',
        'category',
        'with_inactive',
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
        $return['pagination'] = $this->pagination->toArray();

        return $return;
    }

    public function hydrate(array $data = [])
    {
        parent::hydrate($data);
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
        if ($this->with_inactive === 'true') {
            $this->with_inactive = 1;
        }
        if ($this->with_inactive === 'false') {
            $this->with_inactive = 0;
        }
    }

    protected function checkContent(array $data = null)
    {
        $stringOrStringArrays = ['category'];
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