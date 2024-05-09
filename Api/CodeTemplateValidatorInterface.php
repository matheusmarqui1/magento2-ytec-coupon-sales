<?php
/**
 * Copyright (c) 2024 Ytec.
 *
 * @package    Ytec
 * @moduleName CouponSales
 * @author     Matheus Marqui (matheus.marqui@photoweb.fr)
 */
declare(strict_types=1);

namespace Ytec\CouponSales\Api;

use Ytec\CouponSales\Api\Exception\CodeTemplateMismatchException;

/**
 * Interface CodeTemplateValidatorInterface
 * @package Ytec\CouponSales\Api
 * @api
 */
interface CodeTemplateValidatorInterface
{
    /**
     * Validate if the code matches the template of the rule.
     * @param string $ruleTitle
     * @param string $code
     * @param string $template
     * @return void
     * @throws CodeTemplateMismatchException
     */
    public function validate(string $ruleTitle, string $code, string $template): void;
}
