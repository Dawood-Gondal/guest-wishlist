<?php
/**
 * @category    M2Commerce Enterprise
 * @package     M2Commerce_GuestWishlist
 * @copyright   Copyright (c) 2023 M2Commerce Enterprise
 * @author      dawoodgondaldev@gmail.com
 */

namespace M2Commerce\GuestWishlist\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Resource model Class
 */
class Wishlist extends AbstractDb
{
    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('wishlist', 'wishlist_id');
    }

    /**
     * @param int $retentionPeriodInDays
     * @return int
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function removeOlderThan(int $retentionPeriodInDays)
    {
        $connection = $this->getConnection();

        return $connection->delete($this->getMainTable(), [
            sprintf('updated_at < date_sub(CURDATE(), INTERVAL %s Day)', $retentionPeriodInDays),
            new \Zend_Db_Expr(sprintf('wishlist_id NOT IN (SELECT wishlist_id FROM %s)', $this->getTable('wishlist_item'))),
            'customer_id = 0'
        ]);
    }
}
