<?php
/**
 * @category    M2Commerce Enterprise
 * @package     M2Commerce_GuestWishlist
 * @copyright   Copyright (c) 2023 M2Commerce Enterprise
 * @author      dawoodgondaldev@gmail.com
 */

namespace M2Commerce\GuestWishlist\Observer;

use M2Commerce\GuestWishlist\Model\CookieBasedWishlistProvider;
use Magento\Framework\Event\Observer;
use Magento\Wishlist\Model\WishlistFactory;

/**
 * Observer class Merge Wishlist After Login
 */
class MergeWishlistAfterLogin implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var CookieBasedWishlistProvider
     */
    protected $cookieBasedWishlistProvider;


    /**
     * @var WishlistFactory
     */
    protected $wishlistFactory;

    /**
     * @param CookieBasedWishlistProvider $cookieBasedWishlistProvider
     * @param WishlistFactory $wishlistFactory
     */
    public function __construct(
        CookieBasedWishlistProvider $cookieBasedWishlistProvider,
        WishlistFactory $wishlistFactory
    ) {
        $this->cookieBasedWishlistProvider = $cookieBasedWishlistProvider;
        $this->wishlistFactory = $wishlistFactory;
    }

    /**
     * @param Observer $observer
     * @return void
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function execute(Observer $observer)
    {
        try {
            $customer = $observer->getEvent()->getData('customer');
            $guestWishlist = $this->cookieBasedWishlistProvider->getWishlist(false);

            if ($guestWishlist == null) {
                return;
            }

            $customerWishlist = $this->wishlistFactory->create();
            $customerWishlist->loadByCustomerId($customer->getId(), true);
            $guestWishlistItems = $guestWishlist->getItemCollection();

            foreach ($guestWishlistItems as $item)
            {
                $item->setWishlistId($customerWishlist->getId());
                $savedItem = $item->save();

                // if the item exists in customer's wishlist will be returned instead of the guest's one
                if ($savedItem->getId() != $item->getId()) {
                    $item->delete();
                }
            }
        } catch (\Exception $exception) {

        }
    }
}
