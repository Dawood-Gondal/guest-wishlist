<?php
/**
 * @category    M2Commerce Enterprise
 * @package     M2Commerce_GuestWishlist
 * @copyright   Copyright (c) 2023 M2Commerce Enterprise
 * @author      dawoodgondaldev@gmail.com
 */

namespace M2Commerce\GuestWishlist\Model\Wishlist;

/**
 * Class Authentication State
 */
class AuthenticationState implements \Magento\Wishlist\Model\AuthenticationStateInterface
{
    /**
     * @return false
     */
    public function isEnabled()
    {
        return false;
    }
}
