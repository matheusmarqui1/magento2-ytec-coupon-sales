<?php
/**
 * Copyright (c) 2024 Ytec.
 *
 * @package    Ytec
 * @moduleName CouponSales
 * @author     Matheus Marqui (matheus.701@live.com)
 */
declare(strict_types=1);

namespace Ytec\CouponSales\Model;

use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Filesystem\File\ReadInterface;
use Ytec\CouponSales\Api\CouponSaleCsvImporterInterface;
use Magento\Framework\Filesystem\File\ReadFactory;
use Magento\Framework\Exception\InvalidArgumentException;
use Ytec\CouponSales\Api\Data\CouponSaleImportResultInterface;
use Ytec\CouponSales\Model\Config\Source\ImportBehavior;
use Ytec\CouponSales\Api\Data\CouponSaleInterfaceFactory;
use Ytec\CouponSales\Api\CouponSaleRepositoryInterface;
use Ytec\CouponSales\Api\Data\CouponSaleImportResultInterfaceFactory;
use Ytec\CouponSales\Api\ModuleConfigurationInterface;

/**
 * Class CouponSaleCsvImporter
 * @package Ytec\CouponSales\Model
 */
class CouponSaleCsvImporter implements CouponSaleCsvImporterInterface
{
    /**
     * @var ReadFactory
     */
    private ReadFactory $readFactory;

    /**
     * @var CouponSaleInterfaceFactory
     */
    private CouponSaleInterfaceFactory $couponSaleInterfaceFactory;

    /**
     * @var CouponSaleRepositoryInterface
     */
    private CouponSaleRepositoryInterface $couponSaleRepository;

    /**
     * @var CouponSaleImportResultInterfaceFactory
     */
    private CouponSaleImportResultInterfaceFactory $couponSaleImportResultFactory;

    /**
     * @var CouponSaleImportResultInterface
     */
    private CouponSaleImportResultInterface $couponSaleImportResult;

    /**
     * @var ModuleConfigurationInterface
     */
    private ModuleConfigurationInterface $moduleConfiguration;

    /**
     * CouponSaleCsvImporter constructor.
     * @param ReadFactory $readFactory
     * @param CouponSaleInterfaceFactory $couponSaleInterfaceFactory
     * @param CouponSaleRepositoryInterface $couponSaleRepository
     * @param CouponSaleImportResultInterfaceFactory $couponSaleImportResultFactory
     * @param ModuleConfigurationInterface $moduleConfiguration
     */
    public function __construct(
        ReadFactory $readFactory,
        CouponSaleInterfaceFactory $couponSaleInterfaceFactory,
        CouponSaleRepositoryInterface $couponSaleRepository,
        CouponSaleImportResultInterfaceFactory $couponSaleImportResultFactory,
        ModuleConfigurationInterface $moduleConfiguration
    ) {
        $this->readFactory = $readFactory;
        $this->couponSaleInterfaceFactory = $couponSaleInterfaceFactory;
        $this->couponSaleRepository = $couponSaleRepository;
        $this->couponSaleImportResultFactory = $couponSaleImportResultFactory;

        $this->couponSaleImportResult = $this->couponSaleImportResultFactory->create();
        $this->moduleConfiguration = $moduleConfiguration;
    }

    /**
     * {@inheritDoc}
     */
    public function import(
        string $filePath,
        string $importBehavior = ImportBehavior::ADD
    ): ?CouponSaleImportResultInterface {
        $fileRead = $this->readFactory->create($filePath, \Magento\Framework\Filesystem\DriverPool::FILE);

        $rows = $this->prepareData($fileRead);

        $this->couponSaleImportResult->setTotalRowsQuantity(count($rows));

        switch ($importBehavior) {
            case ImportBehavior::ADD:
                return $this->add($rows);
            case ImportBehavior::UPDATE:
                return $this->update($rows);
            case ImportBehavior::MIXED:
                return $this->addOrUpdate($rows);
        }

        return null;
    }

    /**
     * Prepare the data to be imported.
     *
     * @param ReadInterface $fileRead
     * @return array
     * @throws \Exception
     */
    private function prepareData(ReadInterface $fileRead): array
    {
        $rowArray = [];

        /**
         * Read the CSV headers first to extract it from the pointer of the file.
         * Map the values to snake case to match the database columns.
         */
        $headers = array_map(
            fn(string $header) => strtolower(str_replace(' ', '_', $header)),
            $fileRead->readCsv()
        );

        $requiredFields = $this->moduleConfiguration->getImportRequiredFields();

        if (count(array_diff($requiredFields, $headers)) > 0) {
            throw new InvalidArgumentException(
                __('The CSV file must contain the following headers: %1', implode(', ', $requiredFields))
            );
        }

        while ($rowData = $fileRead->readCsv()) {
            $rowArray[] = array_combine($headers, $rowData);
        }

        return $rowArray;
    }

    /**
     * Add new coupon sales.
     *
     * @param array $rows
     * @return CouponSaleImportResultInterface
     */
    private function add(array $rows): CouponSaleImportResultInterface
    {
        foreach ($rows as $line => $row) {
            try {
                $this->addOne($row);
                $this->couponSaleImportResult->incrementImportedRowsQuantity();
            } catch (\Exception $exception) {
                $this->couponSaleImportResult->addError($line, $row['code'], $exception->getMessage());
            }
        }

        return $this->couponSaleImportResult;
    }

    /**
     * Add one coupon sale.
     *
     * @throws CouldNotSaveException
     * @throws LocalizedException
     */
    private function addOne(array $row): void
    {
        $couponSale = $this->couponSaleInterfaceFactory->create()
            ->setData($row);
        $this->couponSaleRepository->save($couponSale);
    }

    /**
     * Update existing coupon sales.
     *
     * @param array $rows
     * @return CouponSaleImportResultInterface
     */
    private function update(array $rows): CouponSaleImportResultInterface
    {
        foreach ($rows as $line => $row) {
            try {
                $couponSale = $this->couponSaleRepository->getCouponSaleByCode($row['code']);
                $couponSale->addData($row);
                $updatedCouponSale = $this->couponSaleInterfaceFactory->create()
                    ->setData($couponSale->getData());
                $this->couponSaleRepository->save($updatedCouponSale);
                $this->couponSaleImportResult->incrementImportedRowsQuantity();
            } catch (\Exception $exception) {
                $this->couponSaleImportResult->addError($line, $row['code'], $exception->getMessage());
            }
        }

        return $this->couponSaleImportResult;
    }

    /**
     * Update one coupon sale.
     *
     * @throws NoSuchEntityException
     * @throws CouldNotSaveException
     * @throws LocalizedException
     */
    private function updateOne(CouponSaleModel $couponSaleModel, array $rowData): void
    {
        $couponSaleModel->addData($rowData);
        $updatedCouponSale = $this->couponSaleInterfaceFactory->create()
            ->setData($couponSaleModel->getData());
        $this->couponSaleRepository->save($updatedCouponSale);
    }

    /**
     * Mixed behavior: Add new coupon sales and update existing ones.
     * This method might be a bit slower than the other two, but it's more flexible.
     *
     * @param array $rows
     * @return CouponSaleImportResultInterface
     */
    private function addOrUpdate(array $rows): CouponSaleImportResultInterface
    {
        foreach ($rows as $line => $row) {
            try {
                $couponSale = $this->couponSaleRepository->getCouponSaleByCode($row['code']);
                $this->updateOne($couponSale, $row);
                $this->couponSaleImportResult->incrementImportedRowsQuantity();
            } catch (NoSuchEntityException $exception) {
                try {
                    $this->addOne($row);
                    $this->couponSaleImportResult->incrementImportedRowsQuantity();
                } catch (\Exception $exception) {
                    $this->couponSaleImportResult->addError($line, $row['code'], $exception->getMessage());
                }
            } catch (\Exception $exception) {
                $this->couponSaleImportResult->addError($line, $row['code'], $exception->getMessage());
            }
        }

        return $this->couponSaleImportResult;
    }
}
