<?php
/**
 * Copyright © 2024 Ytec. All rights reserved.
 *
 * @package    Ytec
 * @moduleName CouponSales
 * @author     Matheus Marqui <matheus.701@live.com>
 */
declare(strict_types=1);

namespace Ytec\CouponSales\Api\Data;

/**
 * Interface CouponSaleInterface
 * @package Ytec\CouponSales\Api\Data
 * @api
 */
interface CouponSaleInterface
{
    /**#@+
     * String constants for property names
     */
    public const ENTITY_ID = "entity_id";
    public const RULE_ID = "rule_id";
    public const CODE_ID = "code_id";
    public const CODE = "code";
    public const STATUS = "status";
    public const PARTNER_NAME = "partner_name";
    public const PARTNER_SALES_PRICE = "partner_sales_price";
    public const CREATED_AT = "created_at";
    public const EXPIRES_AT = "expires_at";
    public const HISTORY = "history";
    public const SALES_ID = "sales_id";
    /**#@-*/

    /**
     * Getter for EntityId.
     *
     * @return int|null
     */
    public function getEntityId(): ?int;

    /**
     * Setter for EntityId.
     *
     * @param int|null $entityId
     *
     * @return CouponSaleInterface
     */
    public function setEntityId(?int $entityId): CouponSaleInterface;

    /**
     * Getter for RuleId.
     *
     * @return int|null
     */
    public function getRuleId(): ?int;

    /**
     * Setter for RuleId.
     *
     * @param int|null $ruleId
     *
     * @return CouponSaleInterface
     */
    public function setRuleId(?int $ruleId): CouponSaleInterface;

    /**
     * Getter for CodeId.
     *
     * @return int|null
     */
    public function getCodeId(): ?int;

    /**
     * Setter for CodeId.
     *
     * @param int|null $codeId
     *
     * @return CouponSaleInterface
     */
    public function setCodeId(?int $codeId): CouponSaleInterface;

    /**
     * Getter for Code.
     *
     * @return string|null
     */
    public function getCode(): ?string;

    /**
     * Setter for Code.
     *
     * @param string|null $code
     *
     * @return CouponSaleInterface
     */
    public function setCode(?string $code): CouponSaleInterface;

    /**
     * Getter for Status.
     *
     * @return string|null
     */
    public function getStatus(): ?string;

    /**
     * Setter for Status.
     *
     * @param string|null $status
     *
     * @return CouponSaleInterface
     */
    public function setStatus(?string $status): CouponSaleInterface;

    /**
     * Getter for PartnerName.
     *
     * @return string|null
     */
    public function getPartnerName(): ?string;

    /**
     * Setter for PartnerName.
     *
     * @param string|null $partnerName
     *
     * @return CouponSaleInterface
     */
    public function setPartnerName(?string $partnerName): CouponSaleInterface;

    /**
     * Getter for PartnerSalesPrice.
     *
     * @return float|null
     */
    public function getPartnerSalesPrice(): ?float;

    /**
     * Setter for PartnerSalesPrice.
     *
     * @param float|null $partnerSalesPrice
     *
     * @return CouponSaleInterface
     */
    public function setPartnerSalesPrice(?float $partnerSalesPrice): CouponSaleInterface;

    /**
     * Getter for CreatedAt.
     *
     * @return string|null
     */
    public function getCreatedAt(): ?string;

    /**
     * Setter for CreatedAt.
     *
     * @param string|null $createdAt
     *
     * @return CouponSaleInterface
     */
    public function setCreatedAt(?string $createdAt): CouponSaleInterface;

    /**
     * Getter for ExpiresAt.
     *
     * @return string|null
     */
    public function getExpiresAt(): ?string;

    /**
     * Setter for ExpiresAt.
     *
     * @param string|null $expiresAt
     *
     * @return CouponSaleInterface
     */
    public function setExpiresAt(?string $expiresAt): CouponSaleInterface;

    /**
     * Getter for History.
     *
     * @return string|null
     */
    public function getHistory(): ?string;

    /**
     * Setter for History.
     *
     * @param string|null $history
     *
     * @return CouponSaleInterface
     */
    public function setHistory(?string $history): CouponSaleInterface;

    /**
     * Adds a new line to the history.
     *
     * @param string $historyLine
     *
     * @return CouponSaleInterface
     */
    public function addHistoryLine(string $historyLine): CouponSaleInterface;

    /**
     * Getter for SalesId.
     *
     * @return string|null
     */
    public function getSalesId(): ?string;

    /**
     * Setter for SalesId.
     *
     * @param string|null $salesId
     *
     * @return CouponSaleInterface
     */
    public function setSalesId(?string $salesId): CouponSaleInterface;
}
