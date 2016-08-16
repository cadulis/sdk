<?php

namespace Cadulis\Sdk\Service;

abstract class AbstractService
{

    /**
     * @var \Cadulis\Sdk\Client\Client
     */
    protected $_client;

    /**
     * @param \Cadulis\Sdk\Client\Client $client
     */
    public function __construct(\Cadulis\Sdk\Client\Client $client = null)
    {
        if ($client !== null) {
            $this->setClient($client);
        }
    }

    /**
     * @param \Cadulis\Sdk\Client\Client $client
     */
    public function setClient(\Cadulis\Sdk\Client\Client $client)
    {
        $this->_client = $client;
    }

    protected function _callClient($routeIdentifier, \Cadulis\Sdk\Model\AbstractModel $model = null)
    {
        if ($this->_client === null) {
            throw new \Cadulis\Sdk\Exception('Client not set');
        }

        return $this->_client->call($routeIdentifier, $model);
    }
}