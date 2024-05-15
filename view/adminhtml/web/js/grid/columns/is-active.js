/**
 * Copyright © 2024 Ytec. All rights reserved.
 *
 * @package    Ytec
 * @moduleName CouponSales
 * @author     Matheus Marqui <matheus.701@live.com>
 */
define([
    'Magento_Ui/js/grid/columns/select'
], function (Column) {
    'use strict';

    // noinspection JSUnresolvedFunction
    return Column.extend({
        defaults: {
            bodyTmpl: 'ui/grid/cells/html'
        },
        getLabel: function (record) {
            let label = this._super(record);

            if (record.is_active === '1') {
                label = `<span class="grid-severity-notice"><span>Active</span></span>`;
            } else {
                label = `<span class="grid-severity-minor"><span>Inactive</span></span>`;
            }

            return label;
        }
    });
});

