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
 * Interface CouponSaleTypeInterface
 * @package Ytec\CouponSales\Api\Data
 * @api
 */
interface CouponSaleTypeInterface
{
    /**#@+
     * String constants for property names
     */
    public const ENTITY_ID = "entity_id";
    public const CODE = "code";
    public const LABEL = "label";
    public const IS_ACTIVE = "is_active";
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
     * @return void
     */
    public function setEntityId(?int $entityId): void;

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
     * @return void
     */
    public function setCode(?string $code): void;

    /**
     * Getter for Label.
     *
     * @return string|null
     */
    public function getLabel(): ?string;

    /**
     * Setter for Label.
     *
     * @param string|null $label
     *
     * @return void
     */
    public function setLabel(?string $label): void;

    /**
     * Getter for IsActive.
     *
     * @return bool|null
     */
    public function getIsActive(): ?bool;

    /**
     * Setter for IsActive.
     *
     * @param bool|null $isActive
     *
     * @return void
     */
    public function setIsActive(?bool $isActive): void;
}
