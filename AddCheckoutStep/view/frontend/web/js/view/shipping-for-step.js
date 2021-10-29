define(
    [
        'ko',
        'uiComponent',
        'underscore',
        'Magento_Checkout/js/model/step-navigator',
        'Magento_Customer/js/model/customer',
        'Magento_Checkout/js/model/quote',
    ],
    function (
        ko,
        Component,
        _,
        stepNavigator,
        customer,
    ) {
        'use strict';
        /**
         * check-login - is the name of the component's .html template
         */
        return Component.extend({
            defaults: {
                template: 'Test_AddCheckoutStep/shipping-for'
            },

            //add here your logic to display step,
            isVisible: ko.observable(true),
            isLogedIn: customer.isLoggedIn(),
            //step code will be used as step content id in the component template
            stepCode: 'ShippingFor',
            //step title value
            stepTitle: 'Shipping For',

            /**
             *
             * @returns {*}
             */
            initialize: function () {
                this._super();
                // register your step
                stepNavigator.registerStep(
                    this.stepCode,
                    //step alias
                    null,
                    this.stepTitle,
                    //observable property with logic when display step or hide step
                    this.isVisible,
                    _.bind(this.navigate, this),
                    /**
                     * sort order value
                     * 'sort order value' < 10: step displays before shipping step;
                     * 10 < 'sort order value' < 20 : step displays between shipping and payment step
                     * 'sort order value' > 20 : step displays after payment step
                     */
                    15
                );

                return this;
            },

            /**
             * The navigate() method is responsible for navigation between checkout step
             * during checkout. You can add custom logic, for example some conditions
             * for switching to your custom step
             */
            navigate: function () {

            },

            /**
             * @returns void
             */
            navigateToNextStep: function () {
                var shipping_for = jQuery('input[name=shipping_for]:checked').val();
                var billing_address = jQuery('#billing-address-same-as-shipping-checkmo');
                var checked_same = billing_address.is(':checked');

                if (shipping_for == 'myself' && !checked_same) {
                    billing_address.trigger('click');
                }

                if (shipping_for == 'another_person' && checked_same) {
                    billing_address.trigger('click');
                }

                stepNavigator.next();
            }
        });
    }
);
