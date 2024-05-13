<?php
/**
 * Copyright © 2024 Ytec. All rights reserved.
 *
 * @package    Ytec
 * @moduleName CouponSales
 * @author     Matheus Marqui <matheus.701@live.com>
 */
declare(strict_types=1);

namespace Ytec\CouponSales\Model\Data;

use Magento\Framework\DataObject;
use Ytec\CouponSales\Api\Data\CouponSaleInterface;

/**
 * Class CouponSaleData
 * @package Ytec\CouponSales\Model\Data
 */
class CouponSaleData extends DataObject implements CouponSaleInterface
{
    /**
     * {@inheritDoc}
     */
    public function getEntityId(): ?int
    {
        return $this->getData(self::ENTITY_ID) === null ? null
            : (int)$this->getData(self::ENTITY_ID);
    }

    /**
     * {@inheritDoc}
     */
    public function setEntityId(?int $entityId): CouponSaleInterface
    {
        $this->setData(self::ENTITY_ID, $entityId);
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getRuleId(): ?int
    {
        return $this->getData(self::RULE_ID) === null ? null
            : (int)$this->getData(self::RULE_ID);
    }

    /**
     * {@inheritDoc}
     */
    public function setRuleId(?int $ruleId): CouponSaleInterface
    {
        $this->setData(self::RULE_ID, $ruleId);
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getCodeId(): ?int
    {
        return $this->getData(self::CODE_ID) === null ? null
            : (int)$this->getData(self::CODE_ID);
    }

    /**
     * {@inheritDoc}
     */
    public function setCodeId(?int $codeId): CouponSaleInterface
    {
        $this->setData(self::CODE_ID, $codeId);
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getCode(): ?string
    {
        return $this->getData(self::CODE);
    }

    /**
     * {@inheritDoc}
     */
    public function setCode(?string $code): CouponSaleInterface
    {
        $this->setData(self::CODE, $code);
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getStatus(): ?string
    {
        return $this->getData(self::STATUS);
    }

    /**
     * {@inheritDoc}
     */
    public function setStatus(?string $status): CouponSaleInterface
    {
        $this->setData(self::STATUS, $status);
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getPartnerName(): ?string
    {
        return $this->getData(self::PARTNER_NAME);
    }

    /**
     * {@inheritDoc}
     */
    public function setPartnerName(?string $partnerName): CouponSaleInterface
    {
        $this->setData(self::PARTNER_NAME, $partnerName);
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getPartnerSalesPrice(): ?float
    {
        return $this->getData(self::PARTNER_SALES_PRICE) === null ? null
            : (float)$this->getData(self::PARTNER_SALES_PRICE);
    }

    /**
     * {@inheritDoc}
     */
    public function setPartnerSalesPrice(?float $partnerSalesPrice): CouponSaleInterface
    {
        $this->setData(self::PARTNER_SALES_PRICE, $partnerSalesPrice);
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getCreatedAt(): ?string
    {
        return $this->getData(self::CREATED_AT) ?: null;
    }

    /**
     * {@inheritDoc}
     */
    public function setCreatedAt(?string $createdAt): CouponSaleInterface
    {
        $this->setData(self::CREATED_AT, $createdAt);
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getExpiresAt(): ?string
    {
        return $this->getData(self::EXPIRES_AT) ?: null;
    }

    /**
     * {@inheritDoc}
     */
    public function setExpiresAt(?string $expiresAt): CouponSaleInterface
    {
        $this->setData(self::EXPIRES_AT, $expiresAt);
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getHistory(): ?string
    {
        return $this->getData(self::HISTORY);
    }

    /**
     * {@inheritDoc}
     */
    public function setHistory(?string $history): CouponSaleInterface
    {
        $this->setData(self::HISTORY, $history);
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function addHistoryLine(string $historyLine): CouponSaleInterface
    {
        $history = $this->getHistory() ?: '';
        $history .= '» ' . $historyLine . PHP_EOL;
        $this->setHistory($history);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getSalesId(): ?string
    {
        return $this->getData(self::SALES_ID);
    }

    /**
     * {@inheritDoc}
     */
    public function setSalesId(?string $salesId): CouponSaleInterface
    {
        $this->setData(self::SALES_ID, $salesId);
        return $this;
    }
}
