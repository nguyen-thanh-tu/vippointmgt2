define(
    [
        'ViMagento_VipPoint/js/view/checkout/summary/vippoint'
    ],
    function (Component) {
        'use strict';

        return Component.extend({

            /**
             * @override
             */
            isDisplayed: function () {
                return !(this.getPureValue() === '' || this.getPureValue() == null || this.getPureValue() === 0);
            }
        });
    }
);