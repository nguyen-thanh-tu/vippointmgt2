<?php

namespace ViMagento\VipPoint\Model\Config\Backend;

class Validate extends \Magento\Framework\App\Config\Value
{
    public function beforeSave()
    {
        $label = $this->getData('field_config/label');

        if ($this->getValue() == '') {
            throw new \Magento\Framework\Exception\ValidatorException(__($label . ' is required.'));
        } else if (!is_numeric($this->getValue())) {
            throw new \Magento\Framework\Exception\ValidatorException(__($label . ' is not a number.'));
        } else if ($this->getValue() < 0) {
            throw new \Magento\Framework\Exception\ValidatorException(__($label . ' is less than 0.'));
        }

        $this->setValue(floatval($this->getValue()));

        parent::beforeSave();
    }
}
