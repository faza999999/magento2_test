<?php

namespace Test\OfflineCustomer\Model\ResourceModel\Grid;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'customer_id';

    protected function _construct()
    {
        $this->_init(
            'Test\OfflineCustomer\Model\Grid',
            'Test\OfflineCustomer\Model\ResourceModel\Grid'
        );
    }
}
