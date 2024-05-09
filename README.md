<div align="center">

![Magento 2 Ytec Coupon Sales](https://i.imgur.com/d8QEHRb.png)
# Ytec Coupon Sales for Magento 2

</div>

<div align="center">

[![Packagist Version](https://img.shields.io/packagist/v/ytec/magento2-ytec-coupon-sales?logo=packagist&style=for-the-badge)](https://packagist.org/packages/ytec/rule-eligibility-check)
[![Packagist Downloads](https://img.shields.io/packagist/dt/ytec/magento2-ytec-coupon-sales.svg?logo=composer&style=for-the-badge)](https://packagist.org/packages/ytec/rule-eligibility-check/stats)
![Supported Magento Versions](https://img.shields.io/badge/magento-%202.4.x-brightgreen.svg?logo=magento&longCache=true&style=for-the-badge)
![License](https://img.shields.io/badge/license-MIT-green?color=%23234&style=for-the-badge)

</div>

## Introduction

This module is a Magento 2 extension that allows you to provide new Magento 2 endpoints to create coupons.
These coupons are named "Coupon Sales" and they can be created by your partners, affiliates or any other third-party integrations.

The flow:
 - You create a "Coupon Sales" rule (basically a cart price rule marked as "Coupon Sales").
 - You provide the rule's ID to your partners.
 - Your partners create a coupon with the rule's ID.
 - The coupon can be used by customers to apply the rule's discount.

## Features

- **Magento 2 Endpoints**: Create, disable and delete "coupon sales" using Magento 2 endpoints.
- **New Cart Price Rule Configuration**: A new configuration to mark a cart price rule as "Coupon Sales".
- **New grid for Coupon Sales**: A new grid to manage your "coupon sales".

## Prerequisites

- PHP 7.4 or higher
- Magento 2.4.x

## Installation

### Composer

1. Run the following command in your Magento 2 root directory:

```bash
composer require ytec/magento2-ytec-coupon-sales
```

2. Enable the module:

```bash
php bin/magento module:enable Ytec_CouponSales
```

3. Run the Magento upgrade command:

```bash
php bin/magento setup:upgrade
```

4. Clean the cache:

```bash
php bin/magento cache:clean
```

## How to Use

1. **Access the Admin Panel**: Go to your Magento 2 Admin Panel.
2. **Navigate to Sales Rules**: Go to `Marketing > Promotions > Cart Price Rules > New`.
3. **Create a New Rule**: Create a new rule and mark it as "Coupon Sales" with the new configuration under "Partnership Sales".
4. **Choose a code template**: Choose a code template for your partners to create coupons (you can turn code template validation off in the configuration Stores > Configuration > Ytec > Coupon Sales > General > Validate Coupon Sales Code Templates).
![Code Template](https://i.imgur.com/Oh5ZQvh.png)
5. **Save the Rule**: Save the rule and get the rule's ID.
6. **Create a Coupon**: Your partners can create coupons using the Magento 2 endpoint `POST /V1/ytec/coupon-sales/coupons` with the following payload:
```json
{
    "couponSales": [
        {
            "rule_id": 1,
            "code": "COUPON_CODE_000000", 
            "expires_at": "2024-12-31 23:59:59", 
            "partner_sales_price": 100
        },
        {
            "rule_id": 1,
            "code": "COUPON_CODE_000001", 
            "expires_at": "2024-12-31 23:59:59", 
            "partner_sales_price": 100
        }
    ]
}
```
- `rule_id`: The rule's ID.
- `code`: The coupon code.
- `expires_at`: The coupon expiration date.
- `partner_sales_price`: The price your partner is selling the coupon for.

7. **Use the Coupon**: Customers can use the coupon code to apply the rule's discount (coupon sales can be used only once).
8. **Manage Coupons**: You can manage the coupons created by your partners in the grid `Ytec > Coupon Sales > Manage Coupon Sales`.
![Manage Coupon Sales](https://i.imgur.com/YdUktLn.png)
- The grid shows the coupon code, the rule's name, the partner's sales price, the expiration date, the status and more.
- You can disable, delete and create coupons sales in the grid.
- You can also see the coupon's usage history by clicking on edit on a coupon sale.
- The partner name is deduced automatically by the partner's API key.
- The Sales ID is automatically deduced by the coupon sale's code and based on a regex that you can define in Stores > Configuration > Ytec > Coupon Sales > General > Sales ID Regex.
![Coupon Sales Configuration](https://i.imgur.com/H2WkSTH.png)


## Other Endpoints

- **GET /V1/ytec/coupon-sales/{couponCode}**: Get a coupon sale by code.
- **PUT /V1/ytec/coupon-sales/{couponCode}/disable**: Disable a coupon sale by code. The coupon can't be used anymore and will get the status "Disabled by partner".
- **DELETE /V1/ytec/coupon-sales/{couponCode}**: Delete a coupon sale by code. The coupon will be deleted from the database, if it's not used. If the configuration indicates as soft delete, the request will be processed as a disable request.

## License

This module is open-source under MIT license. For the full license, please refer to the LICENSE.md file.

## Support and Contribution

For bugs, issues or feature requests, please open an issue on the repository or email matheus.701@live.com for more personalized assistance.

