/**
 * @author @haihv433
 * @copyright Copyright (c) 2020 Goomento (https://store.goomento.com)
 * @package Goomento_MomoPayment
 * @link https://github.com/Goomento/MomoPayment
 */

define(
    [
        'Magento_Checkout/js/view/payment/default',
        'Magento_Ui/js/model/messages',
        'ko',
        'Magento_Checkout/js/model/quote',
        'jquery',
        'Magento_Checkout/js/model/error-processor',
        'Magento_Checkout/js/model/full-screen-loader',
        'Magento_Checkout/js/model/payment/additional-validators',
        'Goomento_Base/js/action/set-payment-method'
    ],
    function (Component, Messages, ko, quote, $,errorProcessor, fullScreenLoader,additionalValidators, setPaymentMethod) {
        'use strict';

        return Component.extend({
            isPlaceOrderActionAllowed: ko.observable(quote.billingAddress() != null),
            redirectAfterPlaceOrder: true,
            defaults: {
                template: 'Goomento_MomoPayment/payment/momo_payment'
            },
            initChildren: function () {
                this.messageContainer = new Messages();
                this.createMessagesComponent();
                return this;
            },
            continueToMomoPaymentGateway: function (x,event) {
                let self = this;

                if (event) {
                    event.preventDefault();
                }

                if (this.validate() && additionalValidators.validate()) {
                    this.selectPaymentMethod();
                    setPaymentMethod(this.messageContainer)
                        .success(function() {
                            self.placeOrder();
                        });
                    return true;
                }
                return false;
            },
            placeOrder: function (x,event) {
                let self = this;

                if (event) {
                    event.preventDefault();
                }

                if (this.validate() &&
                    additionalValidators.validate() &&
                    this.isPlaceOrderActionAllowed() === true
                ) {
                    this.isPlaceOrderActionAllowed(false);
                    this.getPlaceOrderDeferredObject()
                        .done(
                            function () {
                                self.afterPlaceOrder();
                                if (self.redirectAfterPlaceOrder) {
                                    fullScreenLoader.startLoader();
                                    window.location.replace(self._getStartUrl());
                                }
                            }
                        ).always(
                        function () {
                            self.isPlaceOrderActionAllowed(true);
                        }
                    );

                    return true;
                }

                return false;
            },
            _getStartUrl: function() {
                return '/' + window.checkoutConfig.payment.momo_payment.start_url;
            },
            getData: function () {
                let data = {
                    'method': this.getCode(),
                };
                data['additional_data'] = _.extend(data['additional_data'], this.additionalData);

                return data;
            },
            getLogoSrc: function() {
                return window.checkoutConfig.payment.momo_payment.logo_src;
            },
            getTitle:function(){
                return window.checkoutConfig.payment.momo_payment.title;
            },
            getContinueText:function(){
                return "Continue";
            },
            getCode: function() {
                return window.checkoutConfig.payment.momo_payment.code;
            },
            isActive: function() {
                return true;
            }
        });
    }
);
