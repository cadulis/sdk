<?php

namespace Cadulis\Sdk\Model;

abstract class AbstractModel
{

    const MODEL_IDENTIFIER = 'TO_OVERRIDE';

    /**
     * eg : 2004-02-12T15:19:21+00:00
     */
    const ISO_8601_DATE_PATTERN = '#^(?:[1-9]\d{3}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1\d|2[0-8])|(?:0[13-9]|1[0-2])-(?:29|30)|(?:0[13578]|1[02])-31)|(?:[1-9]\d(?:0[48]|[2468][048]|[13579][26])|(?:[2468][048]|[13579][26])00)-02-29)T(?:[01]\d|2[0-3]):[0-5]\d:[0-5]\d(?:Z|[+-][01]\d:[0-5]\d)$#';

    /**
     * eg : 2004-02-12T15:19:21.242Z
     */
    const ISO_8601_DATE_PATTERN_JAVASCRIPT = '/^(\d{4})-(\d{2})-(\d{2})T(\d{2}):(\d{2}):(\d{2})(\.\d{3})?(Z|(\+|-)\d{2}(:?\d{2})?)$/';

    protected $_properties = [];

    public function __construct(array $data = null)
    {
        if ($data !== null) {
            $this->hydrate($data);
        }
    }

    public function hydrate(array $data = [])
    {
        $this->checkContent($data);
        foreach ($this->_properties as $property) {
            if (isset($data[$property])) {
                $this->$property = $data[$property];
            }
        }
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $this->checkContent();
        $return = [];
        foreach ($this->_properties as $property) {
            if ($this->$property !== null) {
                $return[$property] = $this->$property;
            }
        }

        return $return;
    }

    /**
     * @param string $date
     */
    protected function checkDateIso8601($date)
    {
        return preg_match(static::ISO_8601_DATE_PATTERN, $date)
            || preg_match(static::ISO_8601_DATE_PATTERN_JAVASCRIPT, $date);
    }

    protected function checkContent(array $data = null)
    {
        return true;
    }

    public function isValid()
    {
        try {
            $this->checkContent();
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    public function checkIsValid()
    {
        $this->checkContent();
    }

    public function getIdentifier()
    {
        return static::MODEL_IDENTIFIER;
    }

    public function replaceRouteFields(Routes\Route $route)
    {

    }

    protected function checkDateFields(array $fieldNames = [], array $data = null)
    {
        foreach ($fieldNames as $dateField) {
            if ($data !== null && isset($data[$dateField]) && $data[$dateField] != ''
                && !$this->checkDateIso8601($data[$dateField])
            ) {
                throw new \Cadulis\Sdk\Exception($dateField . ' does not match the iso 8601 pattern');
            }
            if ($this->$dateField !== null && $this->$dateField != '' && !$this->checkDateIso8601($this->$dateField)) {
                throw new \Cadulis\Sdk\Exception($dateField . ' does not match the iso 8601 pattern');
            }
        }
    }

}