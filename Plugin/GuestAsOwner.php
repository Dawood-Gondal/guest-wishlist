<?php
/**
 * @category    M2Commerce Enterprise
 * @package     M2Commerce_GuestWishlist
 * @copyright   Copyright (c) 2023 M2Commerce Enterprise
 * @author      dawoodgondaldev@gmail.com
 */

namespace M2Commerce\GuestWishlist\Plugin;

use Magento\Wishlist\Model\Wishlist;

/**
 * Class to make guest as wishlist owner
 */
class GuestAsOwner
{
    /**
     * @param Wishlist $subject
     * @param $result
     * @param $customerId
     * @return mixed|true
     */
    public function afterIsOwner(
        Wishlist $subject,
        $result,
        $customerId
    ) {
        if (!$customerId) {
            return true;
        }

        return $result;
    }
}
