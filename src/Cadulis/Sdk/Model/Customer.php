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
     * @var CustomerOpeningHours
     */
    public $opening_hours;

    /**
     * @var CustomerAssignmentRestriction
     */
    public $assignment_restrictions;

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
        if (!empty($this->opening_hours)) {
            if (!($this->opening_hours instanceof CustomerOpeningHours)) {
                throw new \Cadulis\Sdk\Exception(
                    'invalid opening_hours instance (must be instanceof \Cadulis\Sdk\CustomerOpeningHours'
                );
            }
            $return['opening_hours'] = $this->opening_hours->toString();
        }
        if (!empty($this->assignment_restrictions)) {
            if (!($this->assignment_restrictions instanceof CustomerAssignmentRestriction)) {
                throw new \Cadulis\Sdk\Exception(
                    'invalid opening_hours instance (must be instanceof \Cadulis\Sdk\CustomerAssignmentRestriction'
                );
            }
            $return['assignment_restrictions'] = $this->assignment_restrictions->toString();
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
        if (isset($data['opening_hours'])) {
            $this->opening_hours = new CustomerOpeningHours();
            if (!is_string($data['opening_hours'])) {
                throw new \Exception('Invalid parameter "opening_hours"');
            }
            $this->opening_hours->hydrate($data['opening_hours']);
        }
        if (isset($data['assignment_restrictions'])) {
            $this->assignment_restrictions = new CustomerAssignmentRestriction();
            if (!is_string($data['assignment_restrictions'])) {
                throw new \Exception('Invalid parameter "assignment_restrictions"');
            }
            $this->assignment_restrictions->hydrate($data['assignment_restrictions']);
        }
        foreach ($data as $k => $v) {
            if (!in_array($k, $this->_properties)
                && $k !== 'custom_fields'
                && $k !== 'opening_hours'
                && $k !== 'assignment_restrictions'
            ) {
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