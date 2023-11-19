<?php
/**
 * @category    M2Commerce Enterprise
 * @package     M2Commerce_GuestWishlist
 * @copyright   Copyright (c) 2023 M2Commerce Enterprise
 * @author      dawoodgondaldev@gmail.com
 */

namespace M2Commerce\GuestWishlist\Plugin\Wishlist\Controller\AbstractIndex;

use Magento\Customer\Model\Session;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Response\Http;
use Magento\Wishlist\Model\AuthenticationStateInterface;

/**
 * Plugin Class Remove Redirect
 */
class RemoveRedirectHeader
{
    /**
     * @var AuthenticationStateInterface
     */
    protected $authenticationState;

    /**
     * @var Session
     */
    protected $customerSession;

    /**
     * @var Http
     */
    protected $response;

    /**
     * @param AuthenticationStateInterface $authenticationState
     * @param Session $customerSession
     * @param Http $response
     */
    public function __construct(
        AuthenticationStateInterface $authenticationState,
        Session $customerSession,
        Http $response
    ) {
        $this->authenticationState = $authenticationState;
        $this->customerSession = $customerSession;
        $this->response = $response;
    }

    /**
     * @param ActionInterface $subject
     * @param RequestInterface $request
     * @return void
     * @throws \Magento\Framework\Exception\SessionException
     */
    public function beforeDispatch(
        ActionInterface $subject,
        RequestInterface $request
    ) {
        if (!$this->authenticationState->isEnabled()
            && !$this->customerSession->authenticate()
            && $this->response->isRedirect()
        ) {
            $this->response->setStatusHeader(200)
                ->clearHeader('Location');
        }
    }
}
