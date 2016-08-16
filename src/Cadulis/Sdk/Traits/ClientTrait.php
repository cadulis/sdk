<?php
namespace Cadulis\Sdk\Traits;

trait ClientTrait
{

    /**
     * @var \Cadulis\Sdk\Client\Client
     */
    protected $_client;

    /**
     * @param \Cadulis\Sdk\Client\Client $client
     */
    public function setClient(\Cadulis\Sdk\Client\Client $client)
    {
        $this->_client = $client;
    }

    /**
     * @return \Cadulis\Sdk\Client\Client
     */
    public function getClient()
    {
        return $this->_client;
    }

}