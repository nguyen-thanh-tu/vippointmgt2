define(
    [
        'Magento_Checkout/js/view/summary/abstract-total',
        'Magento_Checkout/js/model/quote',
        'Magento_Catalog/js/price-utils',
        'Magento_Checkout/js/model/totals'
    ],
    function (Component, quote, priceUtils, totals) {
        "use strict";
        var vippoint = totals.getSegment('vippoint');
        return Component.extend({
            defaults: {
                isFullTaxSummaryDisplayed: window.checkoutConfig.isFullTaxSummaryDisplayed || false,
                template: 'ViMagento_VipPoint/checkout/summary/vippoint.html'
            },
            totals: quote.getTotals(),
            isTaxDisplayedInGrandTotal: window.checkoutConfig.includeTaxInGrandTotal || false,
            isDisplayed: function() {
                return this.isFullMode() && this.getPureValue() !== 0;
            },

            getValue: function() {
                return '-'+this.getFormattedPrice(this.getPureValue());
            },
            getPureValue: function() {
                var price;
                if (vippoint.title === '' || vippoint.title == null) {
                    price = 0;
                }else{
                    price = vippoint.value;
                }
                return price;
            },
            getAmout_VipPoint: function(){
                var amout_vippoint;
                if (vippoint.title === '' || vippoint.title == null || vippoint.title === 0) {
                    amout_vippoint = 0;
                }else{
                    amout_vippoint = '( ' + vippoint.title + ' )';
                }
                return amout_vippoint;
            }
        });
    }
);