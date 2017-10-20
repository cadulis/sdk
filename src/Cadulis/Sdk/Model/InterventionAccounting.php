<?php

namespace Cadulis\Sdk\Model;

class InterventionAccounting extends AbstractModel
{
    const MODEL_IDENTIFIER = 'intervention.accounting';

    public $billable;
    public $billable_amount;
    public $billable_transmitted;
    public $billed;
    public $billed_amount;
    public $billed_comment;
    public $payment_sent;
    public $payment_sent_amount;
    public $invoiceable;
    public $invoiceable_amount;
    public $invoiceable_transmitted;
    public $invoiced;
    public $invoiced_amount;
    public $invoiced_comment;
    public $payment_received;
    public $payment_received_amount;


    protected $_properties = [
        'billable',
        'billable_amount',
        'billable_transmitted',
        'billed',
        'billed_amount',
        'billed_comment',
        'payment_sent',
        'payment_sent_amount',
        'invoiceable',
        'invoiceable_amount',
        'invoiceable_transmitted',
        'invoiced',
        'invoiced_amount',
        'invoiced_comment',
        'payment_received',
        'payment_received_amount',
    ];
}
