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
 * Class CodeTemplateMismatchException
 * @package Ytec\CouponSales\Api\Exception
 */
class CodeTemplateMismatchException extends LocalizedException
{
    public const DEFAULT_ERROR_MESSAGE = 'The code template does not match the rule template.';
    public const DETAILED_MISMATCH_MESSAGE = 'The code \'%1\' does not match the template \'%2\' of the rule \'%3\'.';

    /**
     * CodeTemplateMismatchException constructor.
     * @param Phrase|null $phrase
     */
    public function __construct(Phrase $phrase = null)
    {
        parent::__construct($phrase ?? __(self::DEFAULT_ERROR_MESSAGE));
    }

    /**
     * Generate a new exception for a code that does not match the rule template.
     * @param string $poolTitle
     * @param string $code
     * @param string $template
     * @return CodeTemplateMismatchException
     */
    public static function ruleTemplateMismatch(string $poolTitle, string $code, string $template): self
    {
        return new self(__(self::DETAILED_MISMATCH_MESSAGE, $code, $template, $poolTitle));
    }
}
