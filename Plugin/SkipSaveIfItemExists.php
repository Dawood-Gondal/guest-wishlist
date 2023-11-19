<?php
/**
 * @category    M2Commerce Enterprise
 * @package     M2Commerce_GuestWishlist
 * @copyright   Copyright (c) 2023 M2Commerce Enterprise
 * @author      dawoodgondaldev@gmail.com
 */

namespace M2Commerce\GuestWishlist\Plugin;

use Magento\Wishlist\Model\Item;
use Magento\Wishlist\Model\ResourceModel\Item\CollectionFactory;

/**
 * Plugin class SkipSaveIfItemExists
 */
class SkipSaveIfItemExists
{

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        CollectionFactory $collectionFactory
    ) {
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * @param Item $subject
     * @param callable $proceed
     * @return Item
     */
    public function aroundSave(
        Item $subject,
        callable $proceed
    ) {

        $item = $this->getWishlistItem($subject->getWishlistId(), $subject->getProductId());

        if ($item instanceof Item && $item->getId()) {
            return $item;
        }

        return $proceed();
    }

    /**
     * @param $wishlistId
     * @param $productId
     * @return \Magento\Framework\DataObject
     */
    public function getWishlistItem($wishlistId, $productId)
    {
        $collection = $this->collectionFactory->create()
            ->addFieldToFilter('product_id', ['eq' => $productId])
            ->addFieldToFilter('wishlist_id', ['eq' => $wishlistId]);
        $collection->getSelect()
            ->limit(1);

        return $collection->getFirstItem();
    }
}
