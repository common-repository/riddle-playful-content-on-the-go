<?php

namespace src\classes\Landingpage\Type;

use src\classes\Landingpage\RiddleLandingpageManager;

abstract class LandingpageType
{

    protected $type = 'default';
    protected $postPrefix = 'riddle_type_';

    protected $id;
    protected $values;
    protected $defaultValues;

    public function __construct(int $id)
    {
        $this->id = $id;
        $this->values = [];
    }

    public function exists()
    {
        return $this->id !== null && $this->id !== -1;
    }  

    /**
     * @return int returns the ID of the landingpage
     */
    public function update()
    {
        $this->injectValuesFromArray($_POST);

        return RiddleLandingpageManager::update($this);
    }

    public function injectValuesFromArray(array $values) 
    {
        foreach ($this->getOptions() as $optionName) {
            $postName = $this->postPrefix . $optionName;
            $value = isset($values[$postName]) ? $values[$postName] : false;

            if (false === $value || '' === $value) { // value is not set / is empty
                continue;
            }

            if ($this->_valueIsDefault($optionName, $value)) { // we don't need to save this bit of data since its's default
                unset($this->values[$optionName]);

                continue;
            }

            $this->values[$optionName] = is_array($value) ? $value : urldecode($value);
        }
    }

    public function getValue($key, $fallbackToDefault = true, $htmlEncode = true)
    {
        if (!isset($this->values[$key])) {
            if (!$fallbackToDefault) {
                return null;
            }

            $value = $this->getDefaultValue($key);
        } else {
            $value = $this->values[$key];
        }

        if(!is_array($value))  {
            $value = \stripslashes($value);
        }

        return $htmlEncode && !is_array($value) ? \htmlentities($value) : $value;
    }

    public function getArrayValue($key, $fallbackToDefault = true)
    {
        $val = $this->getValue($key, $fallbackToDefault, false);

        return is_array($val) ? $val : json_decode(\stripslashes($val), true);
    }

    public function setValue($key, $value)
    {
        $this->values[$key] = $value;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getAllValues()
    {
        $options = [];

        foreach ($this->getOptions() as $key) {
            $options[$key] = $this->getValue($key);
        }

        return $options;
    }

    public function setValues(array $values)
    {
        $this->values = $values;
    }

    public function getValues()
    {
        return $this->values;
    }

    public function getDefaultValue($key)
    {
        if (!$this->defaultValues) {
            $this->defaultValues = $this->getDefaultValues();
        }

        return isset($this->defaultValues[$key]) ? $this->defaultValues[$key] : null;
    }

    public function getDefaultValues()
    {
        return [];
    }

    private function _valueIsDefault($optionName, $value)
    {
        return $this->getDefaultValue($optionName) === $value;
    }

    public abstract function getOptions();
    public abstract function render();

}