<?php
/**
 * Copyright (c) 2024 Ytec.
 *
 * @package    Ytec
 * @moduleName CouponSales
 * @author     Matheus Marqui <matheus.701@live.com>
 */
declare(strict_types=1);

namespace Ytec\CouponSales\Api\Exception;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Phrase;

/**
 * Class SalesIdExtractionNoMatchException
 * @package Ytec\CouponSales\Api\Exception
 */
class SalesIdExtractionNoMatchException extends LocalizedException
{
    public const DEFAULT_ERROR_MESSAGE = 'Failed to extract the sales ID from the coupon sale code.';
    public const DETAILED_MISMATCH_MESSAGE =
        'The code \'%1\' does not have any match to extract the sales ID with the regex \'%2\'. ' .
        'Please provide a code that we can extract the sales ID from.';

    /**
     * SalesIdExtractionNoMatchException constructor.
     * @param Phrase|null $phrase
     */
    public function __construct(Phrase $phrase = null)
    {
        parent::__construct($phrase ?? __(self::DEFAULT_ERROR_MESSAGE));
    }

    /**
     * Generate a new exception for a code that does not match the regex.
     *
     * @param string $code
     * @param string $regex
     * @return SalesIdExtractionNoMatchException
     */
    public static function doesNotMatch(string $code, string $regex): self
    {
        return new self(__(self::DETAILED_MISMATCH_MESSAGE, $code, $regex));
    }
}
