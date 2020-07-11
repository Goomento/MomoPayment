/**
 * @author @haihv433
 * @copyright Copyright (c) 2020 Goomento (https://store.goomento.com)
 * @package Goomento_MomoPayment
 * @link https://github.com/Goomento/MomoPayment
 */

define([
        'uiComponent',
        'Magento_Checkout/js/model/payment/renderer-list'],
    function ( Component, rendererList ) {
        'use strict';
        rendererList.push(
            {
                type: 'momo_payment',
                component: 'Goomento_MomoPayment/js/view/payment/method-renderer/momo_payment-renderer'
            }
        );
        return Component.extend({});
    }
);
