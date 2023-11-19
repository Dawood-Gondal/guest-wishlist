<?php
/**
 * @category    M2Commerce Enterprise
 * @package     M2Commerce_GuestWishlist
 * @copyright   Copyright (c) 2023 M2Commerce Enterprise
 * @author      dawoodgondaldev@gmail.com
 */

namespace M2Commerce\GuestWishlist\Model;

use Magento\Framework\Math\Random;
use Magento\Framework\Session\SessionManagerInterface;
use Magento\Framework\Stdlib\Cookie\CookieMetadataFactory;
use Magento\Framework\Stdlib\CookieManagerInterface;
use Magento\Wishlist\Model\WishlistFactory;

/**
 * Model Class CookieBasedWishlistProvider
 */
class CookieBasedWishlistProvider
{
    const SECONDS_IN_MINUTE = 60;
    const COOKIE_LIFETIME_IN_MINUTES = 259200;

    /**
     * @var CookieManagerInterface
     */
    protected $cookieManager;

    /**
     * @var WishlistFactory
     */
    protected $wishlistFactory;

    /**
     * @var Random
     */
    protected $random;

    /**
     * @var CookieMetadataFactory
     */
    protected $cookieMetadataFactory;

    /**
     * @var SessionManagerInterface
     */
    protected $sessionManager;

    /**
     * @param CookieManagerInterface $cookieManager
     * @param CookieMetadataFactory $cookieMetadataFactory
     * @param WishlistFactory $wishlistFactory
     * @param SessionManagerInterface $sessionManager
     * @param Random $random
     */
    public function __construct(
        CookieManagerInterface $cookieManager,
        CookieMetadataFactory $cookieMetadataFactory,
        WishlistFactory $wishlistFactory,
        SessionManagerInterface $sessionManager,
        Random $random
    ) {
        $this->cookieManager = $cookieManager;
        $this->wishlistFactory = $wishlistFactory;
        $this->random = $random;
        $this->cookieMetadataFactory = $cookieMetadataFactory;
        $this->sessionManager = $sessionManager;
    }

    /**
     * @param $createNew
     * @return \Magento\Wishlist\Model\Wishlist|null
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getWishlist($createNew = true)
    {
        $wishlist = $this->wishlistFactory->create();

        if ($this->cookieManager->getCookie('wishlist')) {
            $wishlist->load($this->cookieManager->getCookie('wishlist'), 'sharing_code');
        }

        if ($wishlist->getId()) {
            return $wishlist;
        }

        if (!$createNew) {
            return null;
        }

        $wishlist->setCustomerId(0);
        $wishlist->setSharingCode($this->random->getUniqueHash());
        $wishlist->save();
        $this->setCookieWithSharingCode($wishlist->getSharingCode());
        return $wishlist;
    }

    /**
     * @param $sharingCode
     * @return void
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Stdlib\Cookie\CookieSizeLimitReachedException
     * @throws \Magento\Framework\Stdlib\Cookie\FailureToSendException
     */
    public function setCookieWithSharingCode($sharingCode)
    {
        $metadata = $this->cookieMetadataFactory
            ->createPublicCookieMetadata()
            ->setPath($this->sessionManager->getCookiePath())
            ->setDomain($this->sessionManager->getCookieDomain())
            ->setDuration(self::COOKIE_LIFETIME_IN_MINUTES * self::SECONDS_IN_MINUTE);

        $this->cookieManager->setPublicCookie('wishlist', $sharingCode, $metadata);
    }
}
