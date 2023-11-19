<?php
/**
 * @category    M2Commerce Enterprise
 * @package     M2Commerce_GuestWishlist
 * @copyright   Copyright (c) 2023 M2Commerce Enterprise
 * @author      dawoodgondaldev@gmail.com
 */

namespace M2Commerce\GuestWishlist\Controller;

use M2Commerce\GuestWishlist\Model\CookieBasedWishlistProvider;
use Magento\Customer\Model\Session;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Message\ManagerInterface;
use Magento\Wishlist\Model\Wishlist;
use Magento\Wishlist\Model\WishlistFactory;

/**
 * Action class WishlistProvider
 */
class WishlistProvider implements \Magento\Wishlist\Controller\WishlistProviderInterface
{
    /**
     * @var Wishlist
     */
    protected $wishlist;

    /**
     * @var WishlistFactory
     */
    protected $wishlistFactory;

    /**
     * @var Session
     */
    protected $customerSession;

    /**
     * @var ManagerInterface
     */
    protected $messageManager;

    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var CookieBasedWishlistProvider
     */
    protected $cookieBasedWishlistProvider;

    /**
     * @param WishlistFactory $wishlistFactory
     * @param Session $customerSession
     * @param ManagerInterface $messageManager
     * @param RequestInterface $request
     * @param CookieBasedWishlistProvider $cookieBasedWishlistProvider
     */
    public function __construct(
        WishlistFactory $wishlistFactory,
        Session $customerSession,
        ManagerInterface $messageManager,
        RequestInterface $request,
        CookieBasedWishlistProvider $cookieBasedWishlistProvider
    ) {
        $this->request = $request;
        $this->wishlistFactory = $wishlistFactory;
        $this->customerSession = $customerSession;
        $this->messageManager = $messageManager;
        $this->cookieBasedWishlistProvider = $cookieBasedWishlistProvider;
    }

    /**
     * @param $wishlistId
     * @return false|Wishlist|null
     */
    public function getWishlist($wishlistId = null)
    {
        if ($this->wishlist) {
            return $this->wishlist;
        }
        try {
            if (!$wishlistId) {
                $wishlistId = $this->request->getParam('wishlist_id');
            }
            $customerId = $this->customerSession->getCustomerId();
            $wishlist = $this->wishlistFactory->create();

            if (!$customerId) {
                $this->wishlist = $this->cookieBasedWishlistProvider->getWishlist();
                return $this->wishlist;
            }

            if ($wishlistId) {
                $wishlist->load($wishlistId);
            } elseif ($customerId) {
                $wishlist->loadByCustomerId($customerId, true);
            }

            if (!$wishlist->getId() || $wishlist->getCustomerId() != $customerId) {
                throw new NoSuchEntityException(
                    __('The requested Wish List doesn\'t exist.')
                );
            }
        } catch (NoSuchEntityException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            return false;
        } catch (\Exception $e) {
            $this->messageManager->addExceptionMessage($e, __('We can\'t create the Wish List right now.'));
            return false;
        }
        $this->wishlist = $wishlist;
        return $wishlist;
    }
}
