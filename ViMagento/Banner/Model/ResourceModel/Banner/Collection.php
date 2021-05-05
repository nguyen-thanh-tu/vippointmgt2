<?php

namespace ViMagento\Banner\Model\ResourceModel\Banner;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

    protected $_idFieldName = 'id';

    protected function _construct()
    {
        // Model + Resource Model
        $this->_init('ViMagento\Banner\Model\Banner', 'ViMagento\Banner\Model\ResourceModel\Banner');
    }

}
