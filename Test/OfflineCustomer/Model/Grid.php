<?php

namespace Test\OfflineCustomer\Model;

use Test\OfflineCustomer\Api\Data\GridInterface;

class Grid extends \Magento\Framework\Model\AbstractModel implements GridInterface
{

    const CACHE_TAG = 'test_offlinecustomer_post';

    protected $_cacheTag = 'test_offlinecustomer_post';

    protected $_eventPrefix = 'test_offlinecustomer_post';

    protected function _construct()
    {
        $this->_init('Test\OfflineCustomer\Model\ResourceModel\Grid');
    }

    public function getCustomerId()
    {
        return $this->getData(self::CUSTOMER_ID);
    }

    public function setCustomerId($customerId)
    {
        return $this->setData(self::CUSTOMER_ID, $customerId);
    }

    public function getName()
    {
        return $this->getData(self::NAME);
    }

    public function setName($name)
    {
        return $this->setData(self::NAME, $name);
    }

    public function getEmail()
    {
        return $this->getData(self::EMAIL);
    }

    public function setEmail($email)
    {
        return $this->setData(self::EMAIL, $email);
    }

    public function getCreationDate()
    {
        return $this->getData(self::CREATION_DATE);
    }

    public function setCreationDate($creationDate)
    {
        return $this->setData(self::CREATION_DATE, $creationDate);
    }
}
