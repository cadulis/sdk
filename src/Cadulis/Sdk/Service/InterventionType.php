<?php

namespace Cadulis\Sdk\Service;

class InterventionType extends AbstractService
{

    /**
     * @param \Cadulis\Sdk\Model\Request\Interventions|null $searchModel
     * @return \Cadulis\Sdk\Model\Response\Interventions
     * @throws \Cadulis\Sdk\Exception
     */
    public function getAvailable()
    {
        $result = $this->_callClient(\Cadulis\Sdk\Model\Routes\Route::IDENTIFIER_INTERVENTION_TYPE_LIST);
        if (!is_array($result) || !isset($result['interventionTypes'])) {
            throw new \Cadulis\Sdk\Exception('Invalid get response');
        }

        return new \Cadulis\Sdk\Model\InterventionTypes($result['interventionTypes']);
    }


}