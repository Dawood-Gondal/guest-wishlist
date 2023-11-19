<?php
/**
 * @category    M2Commerce Enterprise
 * @package     M2Commerce_GuestWishlist
 * @copyright   Copyright (c) 2023 M2Commerce Enterprise
 * @author      dawoodgondaldev@gmail.com
 */

namespace M2Commerce\GuestWishlist\Plugin;

use M2Commerce\GuestWishlist\Model\CookieBasedWishlistProvider;
use Magento\Customer\Model\Session;
use Magento\Wishlist\Helper\Data;
use Magento\Wishlist\Model\WishlistFactory;

/**
 * Default logic for counting wishlist items to display them in badge uses customer session.
 * This plugin provides logic that counts wishlist items when customer is not logged in.
 */
class CountItemsAndAllowInCartForGuest
{
    /**
     * @var Session
     */
    protected $customerSession;

    /**
     * @var WishlistFactory
     */
    protected $wishlistFactory;

    /**
     * @var CookieBasedWishlistProvider
     */
    protected $cookieBasedWishlistProvider;

    /**
     * @param Session $customerSession
     * @param WishlistFactory $wishlistFactory
     * @param CookieBasedWishlistProvider $cookieBasedWishlistProvider
     */
    public function __construct(
        Session $customerSession,
        WishlistFactory $wishlistFactory,
        CookieBasedWishlistProvider $cookieBasedWishlistProvider,
    ) {
        $this->customerSession = $customerSession;
        $this->wishlistFactory = $wishlistFactory;
        $this->cookieBasedWishlistProvider = $cookieBasedWishlistProvider;
    }

    /**
     * @param Data $subject
     * @param callable $proceed
     * @return bool
     */
    public function aroundIsAllowInCart(Data $subject, callable $proceed)
    {
        return $subject->isAllow();
    }

    /**
     * @param Data $subject
     * @param callable $proceed
     * @return int
     */
    public function aroundGetItemCount(Data $subject, callable $proceed)
    {
        if (!$this->isCustomerGuest()) {
            return $proceed();
        }

        return $this->countWishlistItems();
    }

    /**
     * @return int
     */
    protected function countWishlistItems()
    {
        try {
            $customerId = $this->customerSession->getCustomerId();

            if (!$customerId) {
                $guestWishlist = $this->cookieBasedWishlistProvider->getWishlist();
            } else {
                $guestWishlist = $this->wishlistFactory->create();
                $guestWishlist->loadByCustomerId($customerId, true);
            }

            $collection = $guestWishlist
                ->getItemCollection()
                ->setInStockFilter(true);

            return $collection->count();
        } catch (\Exception $exception) {
        }

        return 0;
    }

    /**
     * @return bool
     */
    public function isCustomerGuest()
    {
        return !$this->customerSession->isLoggedIn();
    }
}
