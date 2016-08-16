<?php

namespace Cadulis\Sdk\Model;

class Api
{

    use \Bixev\LightLogger\LoggerTrait;
    use \Cadulis\Sdk\Traits\ClientTrait;

    public function __construct($client = null, \Bixev\LightLogger\LoggerInterface $logger = null)
    {
        $this->setClient($client);
        $this->setLogger($logger);
    }

    public function newModelIntervention()
    {
        return new Intervention();
    }

    public function newModelCustomer()
    {
        return new Customer();
    }

}