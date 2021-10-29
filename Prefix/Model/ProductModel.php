<?php
namespace Test\Prefix\Model;

use Magento\Catalog\Model\Product;

class ProductModel extends \Magento\Framework\Model\AbstractModel
{
    public function afterGetName(Product $subject, $result)
    {
        return '[Best]'.$result;
    }
}
