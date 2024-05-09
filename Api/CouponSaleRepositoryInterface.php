<?php
/**
 * Copyright (c) 2024 Ytec.
 *
 * @package    Ytec
 * @moduleName CouponSales
 * @author     Matheus Marqui <matheus.701@live.com>
 */
declare(strict_types=1);

namespace Ytec\CouponSales\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\CouldNotDeleteException;
use Ytec\CouponSales\Api\Data\CouponSaleInterface;
use Ytec\CouponSales\Model\CouponSaleModel;

/**
 * Interface CouponSaleRepositoryInterface
 * @package Ytec\CouponSales\Api
 * CouponSale repository interface.
 */
interface CouponSaleRepositoryInterface
{
    /**
     * Get couponsale by id.
     *
     * @param int $id
     * @return CouponSaleModel
     * @throws NoSuchEntityException
     */
    public function getById(int $id): CouponSaleModel;

    /**
     * Save couponsale.
     *
     * @param CouponSaleInterface $couponsale
     * @return CouponSaleModel
     * @throws CouldNotSaveException If an error occurs while saving.
     * @throws LocalizedException If validation fails.
     */
    public function save(CouponSaleInterface $couponsale): CouponSaleModel;

    /**
     * Delete couponsale.
     *
     * @param CouponSaleModel $couponsale
     * @return void
     * @throws \Exception
     */
    public function delete(CouponSaleModel $couponsale): void;

    /**
     * Delete couponsale by id.
     *
     * @param int $id
     * @return void
     * @throws CouldNotDeleteException
     */
    public function deleteById(int $id): void;

    /**
     * Get couponsale by code.
     *
     * @param string $code
     * @return CouponSaleModel
     * @throws NoSuchEntityException
     */
    public function getCouponSaleByCode(string $code): CouponSaleModel;

    /**
     * Get couponsale list.
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return SearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria): SearchResultsInterface;
}
