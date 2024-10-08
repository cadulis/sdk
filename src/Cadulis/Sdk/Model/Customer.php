<?php

namespace Cadulis\Sdk\Model;

class Customer extends AbstractModel
{

    const CUSTOMER_TYPE_COMPANY    = 'company';
    const CUSTOMER_TYPE_INDIVIDUAL = 'individual';
    const CUSTOMER_TYPE_MACHINE    = 'machine';
    const CUSTOMER_TYPES_ALLOWED   = [
        self::CUSTOMER_TYPE_COMPANY,
        self::CUSTOMER_TYPE_INDIVIDUAL,
        self::CUSTOMER_TYPE_MACHINE,
    ];

    public $id;
    public $reference;
    public $active;
    public $type;
    public $name;
    public $first_name;
    public $address;
    public $address_additional;
    public $phone;
    public $mobile;
    public $comment;
    public $email;
    public $category;
    public $portal_access;
    public $portal_code;

    protected $_properties = [
        'id',
        'reference',
        'type',
        'name',
        'first_name',
        'address',
        'address_additional',
        'phone',
        'mobile',
        'comment',
        'email',
        'category',
        'active',
        'portal_access',
        'portal_code',
    ];

    /**
     * @var CustomerCustomFields
     */
    public $custom_fields;

    /**
     * @return array
     */
    public function toArray()
    {
        $return = parent::toArray();
        if (!empty($this->custom_fields)) {
            if (!($this->custom_fields instanceof CustomerCustomFields)) {
                throw new \Cadulis\Sdk\Exception(
                    'invalid custom_fields instance (must be instanceof \Cadulis\Sdk\CustomerCustomFields'
                );
            }
            $return['custom_fields'] = $this->custom_fields->toArray();
        }

        return $return;
    }

    public function hydrate(array $data = [])
    {
        // retro-compatibility
        if (isset($data['email_address'])) {
            $data['email'] = $data['email_address'];
            unset($data['email_address']);
        }
        parent::hydrate($data);
        if (isset($data['custom_fields'])) {
            $this->custom_fields = new CustomerCustomFields;
            if (!is_array($data['custom_fields'])) {
                throw new \Exception('Invalid parameter "custom_fields"');
            }
            $this->custom_fields->hydrate($data['custom_fields']);
        }
        foreach ($data as $k => $v) {
            if (!in_array($k, $this->_properties) && $k !== 'custom_fields') {
                if ($this->custom_fields === null) {
                    $this->custom_fields = new CustomerCustomFields;
                }
                $this->custom_fields[$k] = $v;
            }
        }
    }

    protected function checkContent(array $data = null)
    {
        if (isset($data['type']) && !in_array($data['type'], static::CUSTOMER_TYPES_ALLOWED)) {
            throw new \Exception(
                'Invalid parameter "type", has to be one of' . implode(',', static::CUSTOMER_TYPES_ALLOWED)
            );
        }
    }
}