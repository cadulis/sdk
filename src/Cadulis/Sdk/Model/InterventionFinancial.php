<?php

namespace Cadulis\Sdk\Model;

class InterventionFinancial extends AbstractModel
{
    const MODEL_IDENTIFIER = 'intervention.financial';

    public $manual_price;
    public $manual_cost;


    protected $_properties = [
        'manual_price',
        'manual_cost',
    ];
}
