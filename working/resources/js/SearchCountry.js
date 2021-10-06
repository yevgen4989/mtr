import Popup from "./Popup";

export default class SearchCountry {
    constructor () {
        this.countryInputSelector = '#countrySearch';
        this.countryButtonSelector = '#countryGoTo';
        this.SELECTOR_DELIVERY_ERROR_MSG = '#deliveryRegionFail';
        this.$input = $(this.countryInputSelector);

        if (this.$input.length == 0) {
            return;
        }

        this.$button = $(this.countryButtonSelector);
        this.$msgDeliveryError = new Popup(this.SELECTOR_DELIVERY_ERROR_MSG);

        let idCountry,
            self = this;

        this.$input.autocomplete({
            'serviceUrl': '/wp-json/api/v1/countries/search',
            'onSelect': function (suggestion) {
                idCountry = suggestion.data;
            }
        });

        this.$button.bind('click', function (ev) {
            ev.preventDefault();
            ev.stopPropagation();

            if (idCountry) {
                let $button = $('.button_item_' + idCountry);
                let path = '';

                if ($button.length > 0) {
                    path = $button.attr('href');
                    window.location = path;
                }

                return;
            }

            self.$msgDeliveryError.show();
        });
    }
}
