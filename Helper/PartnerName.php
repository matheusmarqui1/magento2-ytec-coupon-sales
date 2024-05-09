<?php
/**
 * Copyright (c) 2024 Ytec.
 *
 * @package    Ytec
 * @moduleName CouponSales
 * @author     Matheus Marqui (matheus.marqui@photoweb.fr)
 */
declare(strict_types=1);

namespace Ytec\CouponSales\Helper;

use Magento\Framework\App\RequestInterface;
use Magento\Integration\Model\Integration;
use Magento\Integration\Model\IntegrationFactory;
use Magento\Integration\Model\Oauth\Token;
use Magento\Integration\Model\Oauth\TokenFactory;
use Magento\Integration\Model\ResourceModel\Oauth\Token as TokenResource;

/**
 * Class PartnerName
 * @package Ytec\CouponSales\Helper
 */
class PartnerName
{
    /**
     * @var RequestInterface
     */
    private RequestInterface $request;

    /**
     * @var TokenFactory
     */
    private TokenFactory $tokenFactory;

    /**
     * @var IntegrationFactory
     */
    private IntegrationFactory $integrationFactory;

    /**
     * @var TokenResource
     */
    private TokenResource $tokenResource;

    /**
     * PartnerName constructor.
     * @param RequestInterface $request
     * @param TokenFactory $tokenFactory
     * @param IntegrationFactory $integrationFactory
     * @param TokenResource $tokenResource
     */
    public function __construct(
        RequestInterface $request,
        TokenFactory $tokenFactory,
        IntegrationFactory $integrationFactory,
        TokenResource $tokenResource
    ) {
        $this->request = $request;
        $this->tokenFactory = $tokenFactory;
        $this->integrationFactory = $integrationFactory;
        $this->tokenResource = $tokenResource;
    }

    /**
     * Get partner name from request (from integration name within the authorization).
     * @return string|null
     * @noinspection PhpPossiblePolymorphicInvocationInspection
     */
    public function getPartnerNameFromRequest(): ?string
    {
        /** @phpstan-ignore-next-line  */
        $authorization = $this->request->getHeader('Authorization', null);

        if ($authorization !== null && strpos($authorization, 'Bearer ') === 0) {
            $bearerToken = substr($authorization, 7);
            /** @var Token $token */
            $token = $this->tokenFactory->create();
            $this->tokenResource->load($token, $bearerToken, 'token');
            if ($token->getId()) {
                $consumerId = $token->getConsumerId();
                /** @var Integration $integration */
                $integration = $this->integrationFactory->create()->loadByConsumerId($consumerId);
                /** @phpstan-ignore-next-line  */
                return $integration->getId() ? $integration->getName() : null;
            }
        }

        return null;
    }
}
