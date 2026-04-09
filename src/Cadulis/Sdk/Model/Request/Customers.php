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

    /**
     * @var string date (ISO 8601) eg : 2004-02-12T15:19:21+00:00 — UTC in database
     */
    public $updated_at_min;

    /**
     * @var string date (ISO 8601) eg : 2004-02-12T15:19:21+00:00 — UTC in database
     */
    public $updated_at_max;

    /**
     * @var string Field to sort results by. Allowed: 'id', 'updated_at', 'name'
     */
    public $sort_by = 'name';

    /**
     * @var string Sort direction. Allowed: 'asc', 'desc'
     */
    public $sort_order = 'asc';

    protected $_properties = [
        'search_query',
        'category',
        'with_inactive',
        'updated_at_min',
        'updated_at_max',
        'sort_by',
        'sort_order',
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
        $dateFields = ['updated_at_min', 'updated_at_max'];
        $this->checkDateFields($dateFields, $data);

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

        // sort_by validation
        $sortBy = $data['sort_by'] ?? $this->sort_by;
        if ($sortBy !== null && !in_array($sortBy, ['id', 'updated_at', 'name'], true)) {
            throw new \Cadulis\Sdk\Exception('sort_by must be one of: id, updated_at, name');
        }

        // sort_order validation
        $sortOrder = $data['sort_order'] ?? $this->sort_order;
        if ($sortOrder !== null && !in_array($sortOrder, ['asc', 'desc'], true)) {
            throw new \Cadulis\Sdk\Exception('sort_order must be one of: asc, desc');
        }
    }
}