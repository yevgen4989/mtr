export default class SearchCargoType {
    constructor (callbackIfSelect) {
        this.cargoInputSelector = '[name="cargo"]';
        this.countryIdSelector = '[name="country_id"]';
        this.hiddenField = '[name="type_cargo_id"]';

        this.$input = $(this.cargoInputSelector);
        if (this.$input.length == 0) {
            return;
        }

        this.callback = callbackIfSelect;
        this.countryId = $(this.countryIdSelector).val();
        this.$hiddenInput = $(this.hiddenField);
        this.isSuggestion = false;

        let self = this;
        this.$input.autocomplete({
            'serviceUrl': '/wp-json/api/v1/cargo/search?country_id=' + this.countryId,
            'onSelect': function (suggestion) {
                self.$hiddenInput.val(suggestion.data.cargo_id);

                if (typeof self.callback == 'function') {
                    self.callback(suggestion.data);
                }
            },
            'onSearchComplete': function (query, suggestions) {
                if (suggestions.length > 0) {
                    self.isSuggestion = true;
                } else {
                    self.isSuggestion = false;
                    self.$hiddenInput.val('');
                }
            },
        });
    }
}
