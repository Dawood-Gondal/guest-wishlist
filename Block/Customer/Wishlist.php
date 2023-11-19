<?php
/**
 * @category    M2Commerce Enterprise
 * @package     M2Commerce_GuestWishlist
 * @copyright   Copyright (c) 2023 M2Commerce Enterprise
 * @author      dawoodgondaldev@gmail.com
 */

namespace M2Commerce\GuestWishlist\Block\Customer;

/**
 * Block Class
 */
class Wishlist extends \Magento\Wishlist\Block\Customer\Wishlist
{
    /**
     * Parent block is checking customer authorization
     * This method disables it by calling original code from Template class
     */
    public function _toHtml()
    {
        if (!$this->getTemplate()) {
            return '';
        }

        return $this->fetchView($this->getTemplateFile());
    }
}
