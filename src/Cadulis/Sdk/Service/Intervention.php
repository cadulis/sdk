<?php

namespace Cadulis\Sdk\Service;

class Intervention extends AbstractService
{
    public function newSearchInput()
    {
        return new \Cadulis\Sdk\Model\Request\Interventions();
    }

    /**
     * @param \Cadulis\Sdk\Model\Request\Interventions|null $searchModel
     * @return \Cadulis\Sdk\Model\Response\Interventions
     * @throws \Cadulis\Sdk\Exception
     */
    public function search(\Cadulis\Sdk\Model\Request\Interventions $searchModel = null)
    {
        if ($searchModel === null) {
            $searchModel = new \Cadulis\Sdk\Model\Request\Interventions();
        }
        $result = $this->_callClient(\Cadulis\Sdk\Model\Routes\Route::IDENTIFIER_INTERVENTION_LIST, $searchModel);
        if (!is_array($result) || !isset($result['result'])) {
            throw new \Cadulis\Sdk\Exception('Invalid search response');
        }

        return new \Cadulis\Sdk\Model\Response\Interventions($result['result']);
    }

    public function create(\Cadulis\Sdk\Model\Intervention $interventionModel)
    {
        $result = $this->_callClient(\Cadulis\Sdk\Model\Routes\Route::IDENTIFIER_INTERVENTION_CREATE, $interventionModel);
        if (!is_array($result) || !isset($result['intervention'])) {
            throw new \Cadulis\Sdk\Exception('Invalid intervention creation response');
        }

        return new \Cadulis\Sdk\Model\Intervention($result['intervention']);
    }

    public function update(\Cadulis\Sdk\Model\Intervention $interventionModel)
    {
        if($interventionModel->cref === null){
            throw new \Cadulis\Sdk\Exception('Required parameter intervention.cref to update intervention');
        }
        $result = $this->_callClient(\Cadulis\Sdk\Model\Routes\Route::IDENTIFIER_INTERVENTION_UPDATE, $interventionModel);
        if (!is_array($result) || !isset($result['intervention'])) {
            throw new \Cadulis\Sdk\Exception('Invalid intervention update response');
        }

        return new \Cadulis\Sdk\Model\Intervention($result['intervention']);
    }

    public function read(\Cadulis\Sdk\Model\Intervention $interventionModel)
    {
        if($interventionModel->cref === null){
            throw new \Cadulis\Sdk\Exception('Required parameter intervention.cref to read intervention');
        }
        $result = $this->_callClient(\Cadulis\Sdk\Model\Routes\Route::IDENTIFIER_INTERVENTION_READ, $interventionModel);
        if (!is_array($result) || !isset($result['intervention'])) {
            throw new \Cadulis\Sdk\Exception('Invalid intervention get response');
        }

        return new \Cadulis\Sdk\Model\Intervention($result['intervention']);
    }

}