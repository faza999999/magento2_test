<?php

namespace Test\OfflineCustomer\Api\Data;

interface GridInterface
{
    const CUSTOMER_ID = 'customer_id';
    const NAME = 'name';
    const EMAIL = 'email';
    const CREATION_DATE = 'creation_date';

    public function getCustomerId();

    public function setCustomerId($customerId);

    public function getName();

    public function setName($name);

    public function getEmail();

    public function setEmail($email);

    public function getCreationDate();

    public function setCreationDate($creationDate);
}
