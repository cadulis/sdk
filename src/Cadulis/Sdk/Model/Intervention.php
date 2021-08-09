<?php

namespace Cadulis\Sdk\Model;

class Intervention extends AbstractModel
{
    const MODEL_IDENTIFIER          = 'intervention';
    const STATUS_PENDING            = 'pending';
    const STATUS_IN_PROGRESS        = 'in_progress';
    const STATUS_AUTOASSIGN_PENDING = 'autoassign_pending';
    const STATUS_CANCELED           = 'canceled';
    const STATUS_INTERMEDIATE_CLOSE = 'intermediate';
    const STATUS_TERMINATED         = 'terminated';
    const CONTACT_TYPE_CALLIN       = 'callin';
    const CONTACT_TYPE_CALLOUT      = 'callout';

    static protected $STATUS_ALLOWED = [
        self::STATUS_PENDING,
        self::STATUS_IN_PROGRESS,
        self::STATUS_AUTOASSIGN_PENDING,
        self::STATUS_CANCELED,
        self::STATUS_INTERMEDIATE_CLOSE,
        self::STATUS_TERMINATED,
    ];

    public $id;
    public $cref;
    public $is_light_model;
    public $reference;
    public $title;
    public $address;
    public $address_additional;

    /**
     * @var int the more, the more prioritized
     */
    public $priority;

    /**
     * @deprecated
     */
    public $pdf_b64;

    /**
     * @deprecated
     */
    public $withPdf;

    public $reports_b64;
    public $withReports;

    /**
     * @var int duration in seconds
     */
    public $duration;

    /**
     * @var string date (ISO 8601) eg : 2004-02-12T15:19:21+00:00
     */
    public $scheduled_start_at;

    /**
     * @var string date (ISO 8601) eg : 2004-02-12T15:19:21+00:00
     */
    public $scheduled_end_at;

    /**
     * @var string date (ISO 8601) eg : 2004-02-12T15:19:21+00:00
     */
    public $call_reference_at;
    public $with_appointment;
    public $comment;
    public $status;
    /**
     * @var string callin|callout
     */
    public $contact_type;
    public $custom_status1;
    public $custom_status2;
    public $custom_status3;
    public $custom_status4;
    public $custom_status5;
    public $custom_status6;

    protected $_properties = [
        'id',
        'cref',
        'is_light_model',
        'reference',
        'title',
        'address',
        'address_additional',
        'priority',
        'duration',
        'scheduled_start_at',
        'scheduled_end_at',
        'call_reference_at',
        'with_appointment',
        'comment',
        'status',
        'pdf_b64',
        'withPdf',
        'reports_b64',
        'withReports',
        'custom_status1',
        'custom_status2',
        'custom_status3',
        'custom_status4',
        'custom_status5',
        'custom_status6',
        'contact_type',
    ];

    /**
     * @var InterventionType
     */
    public $intervention_type;

    /**
     * @var Customer
     */
    public $customer;

    /**
     * @var InterventionAssignments
     */
    public $assignments;

    /**
     * @var InterventionReport
     */
    public $report;

    /**
     * @var InterventionCustomFields
     */
    public $custom_fields;

    /**
     * @var ScheduledSlots
     */
    public $scheduledSlots;

    /**
     * @var InterventionFinancial
     */
    public $financial;

    /**
     * @var InterventionAccounting
     */
    public $accounting;

    /**
     * @return array
     */
    public function toArray()
    {
        $return = parent::toArray();
        if (!empty($this->intervention_type)) {
            if (!($this->intervention_type instanceof InterventionType)) {
                throw new \Cadulis\Sdk\Exception(
                    'invalid intervention_type instance (must be instanceof \Cadulis\Sdk\InterventionType)'
                );
            }
            $return['intervention_type'] = $this->intervention_type->toArray();
        }
        if (!empty($this->customer)) {
            if (!($this->customer instanceof Customer)) {
                throw new \Cadulis\Sdk\Exception(
                    'invalid customer instance (must be instanceof \Cadulis\Sdk\Customer)'
                );
            }
            $return['customer'] = $this->customer->toArray();
        }
        if (!empty($this->assignments)) {
            if (!($this->assignments instanceof InterventionAssignments)) {
                throw new \Cadulis\Sdk\Exception(
                    'invalid assignments instance (must be instanceof \Cadulis\Sdk\InterventionAssignments)'
                );
            }
            $return['assignments'] = $this->assignments->toArray();
        }
        if (!empty($this->report)) {
            if (!($this->report instanceof InterventionReport)) {
                throw new \Cadulis\Sdk\Exception(
                    'invalid report instance (must be instanceof \Cadulis\Sdk\InterventionReport)'
                );
            }
            $return['report'] = $this->report->toArray();
        }
        if (!empty($this->custom_fields)) {
            if (!($this->custom_fields instanceof InterventionCustomFields)) {
                throw new \Cadulis\Sdk\Exception(
                    'invalid custom_fields instance (must be instanceof \Cadulis\Sdk\InterventionCustomFields)'
                );
            }
            $return['custom_fields'] = $this->custom_fields->toArray();
        }
        if (!empty($this->financial)) {
            if (!($this->financial instanceof InterventionFinancial)) {
                throw new \Cadulis\Sdk\Exception(
                    'invalid financial instance (must be instanceof \Cadulis\Sdk\InterventionFinancial)'
                );
            }
            $return['financial'] = $this->financial->toArray();
        }
        if (!empty($this->accounting)) {
            if (!($this->accounting instanceof InterventionAccounting)) {
                throw new \Cadulis\Sdk\Exception(
                    'invalid accounting instance (must be instanceof \Cadulis\Sdk\InterventionAccounting)'
                );
            }
            $return['accounting'] = $this->accounting->toArray();
        }
        if (!empty($this->scheduledSlots)) {
            if (!($this->scheduledSlots instanceof ScheduledSlots)) {
                throw new \Cadulis\Sdk\Exception(
                    'invalid scheduledSlots instance (must be instanceof \Cadulis\Sdk\ScheduledSlots)'
                );
            }
            $return['scheduledSlots'] = $this->scheduledSlots->toArray();
        }

        return $return;
    }

    public function hydrate(array $data = [])
    {
        if (isset($data['status']) && $data['status'] == 'cancelled') {
            // handle 2 forms "cancelled"/"canceled"
            $data['status'] = static::STATUS_CANCELED;
        }
        parent::hydrate($data);
        if (isset($data['intervention_type'])) {
            $this->intervention_type = new InterventionType;
            if (!is_array($data['intervention_type'])) {
                throw new \Exception('Invalid parameter "intervention_type"');
            }
            $this->intervention_type->hydrate($data['intervention_type']);
        }
        if (isset($data['customer'])) {
            $this->customer = new Customer;
            if (!is_array($data['customer'])) {
                throw new \Exception('Invalid parameter "customer"');
            }
            $this->customer->hydrate($data['customer']);
        }
        if (isset($data['assignments'])) {
            $this->assignments = new InterventionAssignments;
            if (!is_array($data['assignments'])) {
                throw new \Exception('Invalid parameter "assignments"');
            }
            $this->assignments->hydrate($data['assignments']);
        }
        if (isset($data['report'])) {
            $this->report = new InterventionReport;
            if (!is_array($data['report'])) {
                throw new \Exception('Invalid parameter "report"');
            }
            $this->report->hydrate($data['report']);
        }
        if (isset($data['custom_fields'])) {
            $this->custom_fields = new InterventionCustomFields;
            if (!is_array($data['custom_fields'])) {
                throw new \Exception('Invalid parameter "custom_fields"');
            }
            $this->custom_fields->hydrate($data['custom_fields']);
        }
        if (isset($data['financial'])) {
            $this->financial = new InterventionFinancial();
            if (!is_array($data['financial'])) {
                throw new \Exception('Invalid parameter "financial"');
            }
            $this->financial->hydrate($data['financial']);
        }
        if (isset($data['accounting'])) {
            $this->accounting = new InterventionAccounting();
            if (!is_array($data['accounting'])) {
                throw new \Exception('Invalid parameter "accounting"');
            }
            $this->accounting->hydrate($data['accounting']);
        }
        if (isset($data['scheduledSlots'])) {
            $this->scheduledSlots = new ScheduledSlots();
            if (!is_array($data['scheduledSlots'])) {
                throw new \Exception('Invalid parameter "scheduledSlots"');
            }
            $this->scheduledSlots->hydrate($data['scheduledSlots']);
        }
    }

    protected function checkContent(array $data = null)
    {
        $dateFields = ['scheduled_start_at', 'scheduled_end_at'];
        $this->checkDateFields($dateFields, $data);
        if (!empty($data['status']) && !in_array($data['status'], static::$STATUS_ALLOWED)) {
            throw new \Exception(
                'Invalid parameter "status" (' . $data['status'] . '), has to be one of' . implode(
                    ',',
                    static::$STATUS_ALLOWED
                )
            );
        }
        if (!empty($this->status) && !in_array($this->status, static::$STATUS_ALLOWED)) {
            throw new \Exception(
                'Invalid parameter "status" (' . $this->status . '), has to be one of' . implode(
                    ',',
                    static::$STATUS_ALLOWED
                )
            );
        }
        if (!empty($this->contact_type) 
            && !in_array($this->contact_type, [static::CONTACT_TYPE_CALLIN, static::CONTACT_TYPE_CALLOUT])
        ) {
            throw new \Exception(
                'Invalid parameter "contact_type" (' . $this->status . '), has to be one of' . implode(
                    ',',
                    [static::CONTACT_TYPE_CALLIN, static::CONTACT_TYPE_CALLOUT]
                )
            );
        }
        if (!empty($data['scheduledSlots']) && count($data['scheduledSlots']) > 0
            && (!empty($data['scheduled_start_at']) || !empty($data['duration']))
        ) {
            throw new \Exception(
                'Invalid dates given : scheduledSlots and scheduled_start_at/duration cannot be set at the same time'
            );
        }
        if (!empty($this->scheduledSlots) && count($this->scheduledSlots) > 0
            && (!empty($this->scheduled_start_at) || !empty($this->duration))
        ) {
            throw new \Exception(
                'Invalid dates given : scheduledSlots and scheduled_start_at/duration cannot be set at the same time'
            );
        }
    }

    public function replaceRouteFields(Routes\Route $route)
    {
        $url = $route->url;
        if ($this->cref !== null) {
            $url = str_replace(':interventionId', $this->cref, $url);
        }

        $route->url = $url;
    }

    public function setScheduledStart(\DateTime $date)
    {
        $this->scheduled_start_at = $date->format('c');
    }

    public function setScheduledEnd(\DateTime $date)
    {
        $this->scheduled_end_at = $date->format('c');
    }

    public function setReferenceDate(\DateTime $date)
    {
        $this->call_reference_at = $date->format('c');
    }
}
