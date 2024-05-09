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

            /** @type {string[]} */
            let disabledStatuses = ['disabled', 'disabled_by_partner'];

            // noinspection JSUnresolvedVariable
            if (disabledStatuses.includes(record.status)) {
                label = `<span class="grid-severity-minor"><span>${label}</span></span>`;
            } else {
                if (label !== '') {
                    if (record.status === 'available') {
                        label = `<span class="grid-severity-notice"><span>${label}</span></span>`;
                    } else {
                        label = `<span class="grid-severity-major"><span>${label}</span></span>`;
                    }
                }
            }

            return label;
        }
    });
});

