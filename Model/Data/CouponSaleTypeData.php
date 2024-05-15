<?php

namespace Ytec\CouponSales\Model\Data;

use Magento\Framework\DataObject;
use Ytec\CouponSales\Api\Data\CouponSaleTypeInterface;

class CouponSaleTypeData extends DataObject implements CouponSaleTypeInterface
{
    /**
     * Getter for EntityId.
     *
     * @return int|null
     */
    public function getEntityId(): ?int
    {
        return $this->getData(self::ENTITY_ID) === null ? null
            : (int)$this->getData(self::ENTITY_ID);
    }

    /**
     * Setter for EntityId.
     *
     * @param int|null $entityId
     *
     * @return void
     */
    public function setEntityId(?int $entityId): void
    {
        $this->setData(self::ENTITY_ID, $entityId);
    }

    /**
     * Getter for Code.
     *
     * @return string|null
     */
    public function getCode(): ?string
    {
        return $this->getData(self::CODE);
    }

    /**
     * Setter for Code.
     *
     * @param string|null $code
     *
     * @return void
     */
    public function setCode(?string $code): void
    {
        $this->setData(self::CODE, $code);
    }

    /**
     * Getter for Label.
     *
     * @return string|null
     */
    public function getLabel(): ?string
    {
        return $this->getData(self::LABEL);
    }

    /**
     * Setter for Label.
     *
     * @param string|null $label
     *
     * @return void
     */
    public function setLabel(?string $label): void
    {
        $this->setData(self::LABEL, $label);
    }

    /**
     * Getter for IsActive.
     *
     * @return bool|null
     */
    public function getIsActive(): ?bool
    {
        return $this->getData(self::IS_ACTIVE) === null ? null
            : (bool)$this->getData(self::IS_ACTIVE);
    }

    /**
     * Setter for IsActive.
     *
     * @param bool|null $isActive
     *
     * @return void
     */
    public function setIsActive(?bool $isActive): void
    {
        $this->setData(self::IS_ACTIVE, $isActive);
    }
}
