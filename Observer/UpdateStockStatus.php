<?php
namespace Known\OutOfStock\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\CatalogInventory\Api\StockRegistryInterface;

class UpdateStockStatus implements ObserverInterface
{
    protected $stockRegistry;

    public function __construct(
        StockRegistryInterface $stockRegistry
    ) {
        $this->stockRegistry = $stockRegistry;
    }

    public function execute(Observer $observer)
    {
        $stockItem = $observer->getEvent()->getItem();
        $productId = $stockItem->getProductId();

        // Verify if salable qty is 0
        if ($stockItem->getSalableQty() == 0) {
            // Update product status to "Out of Stock"
            $stockStatus = $this->stockRegistry->getStockStatus($productId);
            $stockStatus->setStockStatus(\Magento\CatalogInventory\Model\Stock\Status::STATUS_OUT_OF_STOCK);
            $this->stockRegistry->updateStockStatus($stockStatus);
        }
    }
}
