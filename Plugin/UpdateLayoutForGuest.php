<?php
/**
 * @category    M2Commerce Enterprise
 * @package     M2Commerce_GuestWishlist
 * @copyright   Copyright (c) 2023 M2Commerce Enterprise
 * @author      dawoodgondaldev@gmail.com
 */

namespace M2Commerce\GuestWishlist\Plugin;

use Magento\Framework\View\Result\Page;
use Magento\Wishlist\Controller\Index\Index;

/**
 * Plugin class UpdateLayoutForGuest
 */
class UpdateLayoutForGuest
{
    public const LAYOUT_HANDLER_NAME = 'guest_wishlist_handle';

    /**
     * @var CountItemsAndAllowInCartForGuest
     */
    protected $countItemsForGuestWishlistHelper;

    /**
     * @param CountItemsAndAllowInCartForGuest $countItemsForGuestWishlistHelper
     */
    public function __construct(
        CountItemsAndAllowInCartForGuest $countItemsForGuestWishlistHelper,
    ) {
        $this->countItemsForGuestWishlistHelper = $countItemsForGuestWishlistHelper;
    }

    /**
     * @param Index $subject
     * @param Page $result
     * @return Page
     */
    public function afterExecute(
        Index $subject,
        Page $result
    ) {
        if (!$this->countItemsForGuestWishlistHelper->isCustomerGuest()) {
            return $result;
        }
        $result->getLayout()->getUpdate()->addHandle(self::LAYOUT_HANDLER_NAME);

        return $result;
    }
}
