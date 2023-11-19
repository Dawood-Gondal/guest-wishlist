<?php
/**
 * @category    M2Commerce Enterprise
 * @package     M2Commerce_GuestWishlist
 * @copyright   Copyright (c) 2023 M2Commerce Enterprise
 * @author      dawoodgondaldev@gmail.com
 */

namespace M2Commerce\GuestWishlist\Cron;

use M2Commerce\GuestWishlist\Model\ResourceModel\Wishlist;

class WishlistsCleanup
{
    const GUEST_WISHLIST_EMPTY_WISHLISTS_RETENTION_PERIOD = '1';

    /**
     * @var Wishlist
     */
    protected $resourceModel;

    /**
     * @param Wishlist $resourceModel
     */
    public function __construct(
        Wishlist $resourceModel
    ) {
        $this->resourceModel = $resourceModel;
    }

    /**
     * @return void
     */
    public function execute()
    {
        $this->resourceModel->removeOlderThan(self::GUEST_WISHLIST_EMPTY_WISHLISTS_RETENTION_PERIOD);
    }
}
