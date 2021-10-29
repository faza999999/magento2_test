<?php
namespace Test\AddProductToCart\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Data\Form\FormKey;
use Magento\Checkout\Model\SessionFactory;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Catalog\Api\Data\ProductInterfaceFactory;
use Magento\Catalog\Api\ProductRepositoryInterface;
use \Magento\Catalog\Api\Data\ProductLinkInterface;
use Magento\Framework\Controller\ResultFactory;

class Index extends Action
{
    protected $formKey;
    protected $checkoutSession;
    protected $cartRepository;
    protected $productFactory;
    protected $productRepository;
    protected $productLinkInterface;

    public function __construct(
        Context $context,
        FormKey $formKey,
        SessionFactory $checkoutSession,
        CartRepositoryInterface $cartRepository,
        ProductInterfaceFactory $productFactory,
        ProductRepositoryInterface $productRepository,
        ProductLinkInterface $productLinkInterface,
        ResultFactory $resultFactory
    ) {
        $this->formKey = $formKey;
        $this->checkoutSession = $checkoutSession;
        $this->cartRepository = $cartRepository;
        $this->productFactory = $productFactory;
        $this->productRepository = $productRepository;
        $this->productLinkInterface = $productLinkInterface;
        $this->resultFactory = $resultFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $session = $this->checkoutSession->create();
        $quote = $session->getQuote();
        $productId = $this->createSimpleProduct();
        $product = $this->productRepository->getById($productId);
        $quote->addProduct($product, 1);
        $this->cartRepository->save($quote);
        $session->replaceQuote($quote)->unsLastRealOrderId();
        $result = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        return $result;
    }

    public function createSimpleProduct()
    {
        $product = $this->productFactory->create();
        $product->setSku('sku'.uniqid());
        $product->setCustomAttribute('guarantee_terms', 'test');
        $product->setName(uniqid());
        $product->setWebsiteIds([1]);
        $product->setAttributeSetId(4);
        $product->setStatus(1);
        $product->setWeight(10);
        $product->setVisibility(4);
        $product->setTaxClassId(0);
        $product->setTypeId('simple');
        $product->setPrice(100);
        $product->setStockData(
            [
                'use_config_manage_stock' => 0,
                'manage_stock' => 1,
                'is_in_stock' => 1,
                'qty' => 1
            ]
        );
        $product = $this->productRepository->save($product);
        return $product->getId();
    }

    public function createConfigurableProduct()
    {
        $configurable_product = $this->productFactory->create();
        $configurable_product->setSku('sku'.uniqid());
        $configurable_product->setName('CONFIG PRODUCT NAME'.uniqid());
        $configurable_product->setAttributeSetId(4);
        $configurable_product->setStatus(1);
        $configurable_product->setTypeId('configurable');
        $configurable_product->setPrice(0);
        $configurable_product->setWebsiteIds([1]);
        $configurable_product->setCategoryIds([2]);
        $configurable_product->setStockData([
                'use_config_manage_stock' => 0,
                'manage_stock' => 1,
                'is_in_stock' => 1,
                'qty' => 1
            ]);
        $configurable_product->setStatus(1);
        $color_attr_id = $configurable_product->getResource()->getAttribute('color')->getId();

        $configurable_product->getTypeInstance()->setUsedProductAttributeIds([$color_attr_id], $configurable_product);

        $configurableAttributesData =
            $configurable_product->getTypeInstance()
            ->getConfigurableAttributesAsArray($configurable_product);
        $configurable_product->setCanSaveConfigurableAttributes(true);
        $configurable_product->setConfigurableAttributesData($configurableAttributesData);
        $configurableProductsData = [];
        $configurable_product->setConfigurableProductsData($configurableProductsData);
        $configurable_product->save();
        $productId = $configurable_product->getId();
        $associatedProductIds = [3, 5, 7];
        $configurable_product = $this->productRepository->getById($productId);
        $configurable_product->setAssociatedProductIds($associatedProductIds);
        $configurable_product->setCanSaveConfigurableAttributes(true);
        $configurable_product->save();
        return $configurable_product->getId();
    }

    public function createGroupedProduct()
    {
        $grouped_product = $this->productFactory->create();
        $grouped_product->setName('Grouped Product Name'.uniqid());
        $grouped_product->setSku('my-sku'.uniqid());
        $grouped_product->setTypeId('grouped');
        $grouped_product->setAttributeSetId(4);
        $grouped_product->setStatus(1);
        $grouped_product->setWebsiteIds([1]);
        $grouped_product->setVisibility(4);
        $grouped_product->setStatus(1);
        $grouped_product->setStockData(
            [
                'use_config_manage_stock' => 0,
                'manage_stock' => 1,
                'min_sale_qty' => 1,
                'max_sale_qty' => 2,
                'is_in_stock' => 1,
                'qty' => 1,
            ]
        );
        $grouped_product->save();
        $associated_id = [3, 5, 7];
        $associated_array = [];
        $associated_product_position = 0;
        foreach ($associated_id as $product_id) {
            $associated_product_position++;
            $product_repository_interface = $this->productRepository->getById($product_id);
            $product_link_interface = $this->productLinkInterface;
            $product_link_interface->setSku($grouped_product->getSku())
                ->setLinkType('associated')
                ->setLinkedProductSku($product_repository_interface->getSku())
                ->setLinkedProductType($product_repository_interface->getTypeId())
                ->setPosition($associated_product_position)
                ->getExtensionAttributes()
                ->setQty(1);
            $associated_array[] = $product_link_interface;
        }
        $grouped_product->setProductLinks($associated_array);
        $grouped_product->save();
        return $grouped_product->getId();
    }
}
