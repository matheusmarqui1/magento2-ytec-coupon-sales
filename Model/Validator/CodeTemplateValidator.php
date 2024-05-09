<?php
/**
 * Copyright (c) 2024 Ytec.
 *
 * @package    Ytec
 * @moduleName CouponSales
 * @author     Matheus Marqui (matheus.marqui@photoweb.fr)
 */
declare(strict_types=1);

namespace Ytec\CouponSales\Model\Validator;

use Ytec\CouponSales\Api\CodeTemplateValidatorInterface;
use Ytec\CouponSales\Api\Exception\CodeTemplateMismatchException;

/**
 * Class CodeTemplateValidator
 * @package Ytec\CouponSales\Model\Validator
 */
class CodeTemplateValidator implements CodeTemplateValidatorInterface
{
    /**
     * {@inheritdoc}
     */
    public function validate(string $ruleTitle, string $code, string $template): void
    {
        $pattern = preg_quote($template, '/');
        $pattern = str_replace(['\{L\}', '\{D\}', '\[D\]'], ['[A-Za-z]', '\d', '(\d)?'], $pattern);
        $pattern = '/^' . $pattern . '$/';

        $isValid = preg_match($pattern, $code) === 1;

        if (!$isValid) {
            throw CodeTemplateMismatchException::ruleTemplateMismatch($ruleTitle, $code, $template);
        }
    }
}
