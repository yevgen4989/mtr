import Range from "./Range";
import SearchCargoType from "./SearchCargoType";
import Popup from "./Popup";
import HintWindow from "./HintWindow";
import FormElementController from "./FormElementController";

export default class Calculator {
    constructor () {
        this.SELECTOR_INPUT_CARGO = '[name="cargo"]';
        this.SELECTOR_INPUT_CARGO_ID = '[name="type_cargo_id"]';
        this.SELECTOR_RANGE_CARGO_WEIGHT = '.cargo_weight';
        this.SELECTOR_RANGE_CARGO_VOLUME = '.cargo_volume';
        this.SELECTOR_INPUT_CITY_FROM = '[name="city_from"]';
        this.SELECTOR_INPUT_CITY_TO = '[name="city_to"]';
        this.SELECTOR_NAME = '[name="name"]';
        this.SELECTOR_PHONE = '[name="phone"]';
        this.SELECTOR_EMAIL = '[name="email"]';
        this.SELECTOR_COUNTRY_ID = '[name="country_id"]';
        this.SELECTOR_PRICE = '.price';
        this.SELECTOR_BTN_SEND = '.send';
        this.SELECTOR_ANOUNCEMENT = '.announcement';
        this.SELECTOR_INPUT_PLACE = '.slider__input-place';
        this.SELECTOR_ERROR_WINDOW = '#errors';
        this.SELECTOR_INFO = '.info';
        this.SELECTOR_TOTAL_PRICE_BLOCK = '.total-block__price';
        this.SELECTOR_WEIGHT = '#weight';
        this.SELECTOR_VOLUME = '#volume';
        this.SELECTOR_ERROR_CARGOTYPE_INPUT = '#emptyCargoType';
        this.SELECTOR_HINT_WINDOW = '.hint-window';
        this.SELECTOR_HINT_WINDOW_BTN = '.button';
        this.SELECTOR_MORE_THAN_MAX = '#moreThanMax';
        this.SELECTOR_PANEL = '.panel:not(:first)';
        this.SELECTOR_FIRST_PANEL = '.panel:first';
        this.SELECTOR_CARGO_TYPE_IS_EMPTY_WND = '#cargoTypeIsEmty';
        this.SELECTOR_WINDOW_WEIGHT_TOO_LIGHT = '#weightTooLight';
        this.SELECTOR_TYPE_ITEM = '.type_item';
        this.SELECTOR_WEIGHT_ITEM = '.weight_item';
        this.SELECTOR_VOLUME_ITEM = '.volume_item';
        this.CLASS_DISABLED_PANEL = 'panel_disabled';
        this.CLASS_ERROR_PANEL = 'panel_error';
        this.CONST_MIN_WEIGHT = 50;
        this.CONST_MIN_WEIGHT_FOR_CLIENT = 10;
        this.templateRange =
          '<div class="panel panel__as-announcement-item" id="">' +
            '<div class="panel__data panel__data_full panel__data_radial-white">' +
              '<div class="form-group">' +
                '<div class="form-group__label"></div>' +
                '<div class="slider cargo_volume">' +
                  '<div class="slider__content-place">' +
                    '<div class="slider__input-place"></div>' +
                    '<div class="slider__explanation"></div>' +
                '</div>' +
                '<div class="slider__content-place">' +
                  '<div class="slider__toggle">' +
                    '<div class="slider__toggle__background">' +
                      '<div class="slider__toggle__background__selected"></div>' +
                    '</div>' +
                    '<span class="slider__toggle__pin"></span>' +
                  '</div>' +
                '</div>' +
              '</div>' +
            '</div>' +
            '</div>' +
          '</div>';
        this.$itemCargoInput = $(this.SELECTOR_INPUT_CARGO);
        if (this.$itemCargoInput.length == 0) {
            return;
        }

        this.$panels = $(this.SELECTOR_PANEL);
        this.$firstPanel = $(this.SELECTOR_FIRST_PANEL);
        this.disabledAllPanels();

        let
            self = this,
            windowMoreThanMax = new Popup(this.SELECTOR_MORE_THAN_MAX),
            windowLessThanMin = new Popup(this.SELECTOR_WINDOW_WEIGHT_TOO_LIGHT);
        windowMoreThanMax.showed = 0;

        this.$weight = $(this.SELECTOR_WEIGHT);
        this.$volume = $(this.SELECTOR_VOLUME);
        this.$itemCargoIdInput = $(this.SELECTOR_INPUT_CARGO_ID);
        this.$inputCountryId = $(this.SELECTOR_COUNTRY_ID);
        this.rangeWeight = new Range(this.SELECTOR_RANGE_CARGO_WEIGHT, 10, 2000, undefined, 20000, function () {
            if (windowMoreThanMax.isClose) {
                if (windowMoreThanMax.showed === 0) {
                    windowMoreThanMax.show();
                    windowMoreThanMax.showed++;
                }

            }
        }, false, function () {
            windowLessThanMin.show();
        });
        this.ranges = new Array(0);
        this.variable_params = null;

        this.rangeVolume = new Range(this.SELECTOR_RANGE_CARGO_VOLUME, 1, 100, undefined, 100, undefined, false);
        this.$inputPlaceWeight = $(this.SELECTOR_RANGE_CARGO_WEIGHT).find(this.SELECTOR_INPUT_PLACE);
        this.$inputPlaceVolume = $(this.SELECTOR_RANGE_CARGO_VOLUME).find(this.SELECTOR_INPUT_PLACE);
        this.$cityFromInput = $(this.SELECTOR_INPUT_CITY_FROM);
        this.$cityToInput = $(this.SELECTOR_INPUT_CITY_TO);
        this.$nameInput = $(this.SELECTOR_NAME);
        this.$phoneInput = $(this.SELECTOR_PHONE);
        this.$emailInput = $(this.SELECTOR_EMAIL);
        this.$price = $(this.SELECTOR_PRICE);//.first();
        this.$sendBtn = $(this.SELECTOR_BTN_SEND);
        this.$announcement = $(this.SELECTOR_ANOUNCEMENT);
        this.$infoElements = $(this.SELECTOR_INFO);
        this.$typesItem = $(this.SELECTOR_TYPE_ITEM);
        this.$weightItem = $(this.SELECTOR_WEIGHT_ITEM);
        this.$volumeItem = $(this.SELECTOR_VOLUME_ITEM);

        this.hintWindow = new HintWindow(this.SELECTOR_HINT_WINDOW);
        this.$hintWindowBtn = $(this.SELECTOR_HINT_WINDOW).find(this.SELECTOR_HINT_WINDOW_BTN);

        this.$totalPriceBlock = $(this.SELECTOR_TOTAL_PRICE_BLOCK);

        this.messageBag = [];

        this.routeCostPerKilo = this.$announcement.data('route-price');
        this.costPerKilo = 0;
        this.costPerCubeMeter = 0;
        this.need_cube_meter = false;
        this.need_volume_and_weight = false;
        this.isSuggestion = false;
        this.isAddWithoutCalculate = false;
        this.paramPrices = [];

        this.$typesItem.bind('click',  function (ev) {
            ev.preventDefault();
            ev.stopPropagation();
            let id = $(this).data('id'),
                title = $(this).text();
            self.$itemCargoIdInput.val(id);
            self.$itemCargoInput.val(title);
            self.$itemCargoInput.trigger('change.autocomplete');
        });
        this.$weightItem.bind('click', function (ev) {
            ev.preventDefault();
            ev.stopPropagation();
            let 
                weight = $(this).data('weight');
            self.rangeWeight.setValue(weight);
        });
        this.$volumeItem.bind('click', function (ev) {
            ev.preventDefault();
            ev.stopPropagation();
            let 
                volume = $(this).data('volume');
            self.rangeVolume.setValue(volume);
        });
        let cargoTypes = new SearchCargoType(function (data) {
            self.setPrices(data);
        });
        this.$hintWindowBtn.bind('click', function (ev) {
            ev.preventDefault();
            ev.stopPropagation();
            self.setWithoutCalculate();
        });
        this.$panels.bind('click', function () {
            if ($(this).hasClass(self.CLASS_DISABLED_PANEL)) {
                self.$firstPanel.addClass(self.CLASS_ERROR_PANEL);
                self.$firstPanel.bind('click', function (ev) {
                    $(this).removeClass(self.CLASS_ERROR_PANEL);
                    $(this).unbind('click');
                });
                new Popup(self.SELECTOR_CARGO_TYPE_IS_EMPTY_WND).show();
            }
        });
        this.$itemCargoInput.bind('change.autocomplete', function (ev) {
            self.isSuggestion = cargoTypes.isSuggestion;
            if (!self.isSuggestion && self.$itemCargoIdInput.val() == '' && self.$itemCargoInput.val() != '') {
                self.hintWindow.show();
            } else {
                self.hintWindow.hide();
                self.removeWithoutCalculate();
            }
        });
        this.$infoElements.bind('click', function (ev) {
            ev.preventDefault();
            ev.stopPropagation();

            let
                selector = $(this).attr('href');
            new Popup(selector).show();
        });
        this.$itemCargoInput.bind('change', function (ev) {
            if ($(this).val() == '') {
                self.$itemCargoIdInput.val('');
                self.calculateSum();
                return;
            }

            self.enabledAllPanels();
        });
        this.$inputPlaceVolume.bind('rangeChange', function (ev) {
            self.calculateSum();
        });
        this.$inputPlaceWeight.bind('rangeChange', function (ev) {
            self.calculateSum();
        });
        this.$sendBtn.bind('click', function (ev) {
            ev.stopPropagation();
            ev.preventDefault();

            if (self.validate()) {
                if (isNaN(parseInt(self.$itemCargoIdInput.val())) && !self.isAddWithoutCalculate) {
                    let errorPopup = new Popup(this.SELECTOR_ERROR_CARGOTYPE_INPUT);
                    errorPopup.show();
                }

                self.sendForm();
            } else {
                self.showErrorMessages();
            }
        });

    }

    disabledAllPanels() {
        this.$panels.addClass(this.CLASS_DISABLED_PANEL);
    }
    enabledAllPanels() {
        this.$panels.removeClass(this.CLASS_DISABLED_PANEL);
    }
    setWithoutCalculate() {
        this.isAddWithoutCalculate = true;
        this.$totalPriceBlock.css({
            'opacity': 0,
        });
        this.showWeight();
        this.showVolume();
        this.need_volume_and_weight = true;

        this.hintWindow.hide();
    }
    removeWithoutCalculate() {
        this.isAddWithoutCalculate = false;
        this.$totalPriceBlock.css({
            'opacity': 1,
        });
        this.need_volume_and_weight = false;
    }
    getData() {
        let data = {};
        data.action = 'lead_create';
        if (this.need_cube_meter) {
            data.volume = this.rangeVolume.getValue();
            data.weight = 0;
        } else {
            data.weight = this.rangeWeight.getValue();
            data.volume = 0;
        }

        if (this.need_volume_and_weight) {
            data.volume = this.rangeVolume.getValue();
            data.weight = this.rangeWeight.getValue();
        }

        data.type_cargo_id = this.$itemCargoIdInput.val();
        data.need_container = false;
        data.country_id = this.$inputCountryId.val();
        if (this.$price.length > 1) {
            data.price_per_kilo = this.$price.first().text();
        } else {
            data.price_per_kilo = this.$price.text();
        }
        
        data.name_customer = this.$nameInput.val();
        data.phone = this.$phoneInput.val();
        data.email = this.$emailInput.val();
        data.city_to = this.$cityToInput.val();
        data.city_from = this.$cityFromInput.val();
        data.user_type_cargo = this.$itemCargoInput.val();
        data.variable_attributes = new Array(0);
        data.referer = referer === '' ? '' : referer;
        if (this.ranges.length > 0) {
            for(let i = 0; i < this.ranges.length; i++) {
                data.variable_attributes.push({
                    'name': this.ranges[i].$element.find('.form-group__label').text(),
                    'value': this.ranges[i].getValue(),
                });
            }
        }

        return data;
    }
    setPrices(data) {
        this.costPerKilo = data.price_per_kilo;
        this.costPerCubeMeter = data.price_per_cube_meter;
        this.need_cube_meter = data.need_cube_meter;
        this.need_volume_and_weight = data.need_volume_and_weight;

        if (data.variable_params) {
            this.variable_params = JSON.parse(data.variable_params);
            this.addVariableParams();
        } else {
            this.removeVariableParams();
        }

        if (data.prices) {
            try {
                let tmpPrices = JSON.parse(data.prices);
                this.paramPrices = tmpPrices['items'];
            } catch (e) {
                console.log(e);
            }
        }

        if (data.need_volume_and_weight) {
            this.showWeight();
            this.showVolume();
            this.calculateSum();
        } else {
            if (data.need_cube_meter) {
                this.hideWeight();
                this.showVolume();
            } else {
                this.hideVolume();
                this.showWeight();
            }
        }
    }
    removeVariableParams() {
        if (this.ranges.length > 0) {
            for (let i = 0; i < this.ranges.length; i++) {
                this.ranges[i].$element.remove();
            }

            this.ranges = new Array(0);
        }
    }
    addVariableParams() {
        this.removeVariableParams();

        if (this.ranges.length > 0) {
            for (let i = 0; i < this.ranges.length; i++) {
                this.ranges[i].$element.remove();
            }

            this.ranges = new Array(0);
        }


        for (let i = 0; i < this.variable_params.items.length; i++) {
            let $tmpRange = $(this.templateRange);

            $tmpRange.attr('id', 'item_' + i);
            $tmpRange.find('.form-group__label').text(this.variable_params.items[i].name_param);
            $tmpRange.find('.slider__input-place').text(this.variable_params.items[i].min_value);
            $tmpRange.find('.slider__explanation').text(this.variable_params.items[i].unit);
            $(this.SELECTOR_WEIGHT).after($tmpRange);
            this.ranges.push(new Range('#item_' + i, this.variable_params.items[i].min_value, this.variable_params.items[i].max_value));
            let $tmpPanel = $tmpRange.find(FormElementController.SELECTOR_PANEL_DATA());
            FormElementController.addActionPanel($tmpPanel);
        }

    }
    showWeight() {
        this.$weight.removeAttr('style');
    }
    hideWeight() {
        this.rangeWeight.setToDefault();
        this.$weight.css({
            'display': 'none'
        });
    }
    showVolume() {
        this.$volume.removeAttr('style');
    }
    hideVolume () {
        this.rangeVolume.setToDefault();
        this.$volume.css({
            'display': 'none'
        });
    }
    validate() {
        let noErrors = true;

        if (this.rangeVolume.getValue() == 0 && this.rangeWeight.getValue() == 0) {
            noErrors = false;
            this.messageBag.push('Укажите вес или объем груза.');
        }

        if (this.$cityFromInput.val() == '') {
            noErrors = false;
            this.messageBag.push('Укажите город отправления.');
        }

        if (this.$cityToInput.val() == '') {
            noErrors = false;
            this.messageBag.push('Укажите город назначения');
        }

        if (this.$nameInput.val() == '') {
            noErrors = false;
            this.messageBag.push('Укажите имя контактного лица.');
        }

        if (this.$phoneInput.val() == '' && this.$emailInput.val() == '') {
            noErrors = false;
            this.messageBag.push('Укажите контактный телефон или email.');
        }

        if (this.$emailInput.val() != '') {
            let
                email = this.$emailInput.val();

            if (email.indexOf('@') < 1) {
                noErrors = false;
                this.messageBag.push('Неверно указан email адрес')
            }
        }

        if (this.$phoneInput.val() != '') {
            let
                phone = this.$phoneInput.val();
            if(phone.match(/\w\W/g)) {
                noErrors = false;
                this.messageBag.push('Неверно указан телефон');
            }
        }


        return noErrors;
    }
    showErrorMessages() {
        let popup = new Popup(this.SELECTOR_ERROR_WINDOW);
        let errors = '';

        for(let i = 0; i < this.messageBag.length; i++) {
            errors += '<li class="list__item">' + this.messageBag[i] + '</li>';
        }

        errors = '<ul class="list list_vertical">' + errors + '</ul>';
        popup.setContent(errors).show();
        this.messageBag = [];
    }
    calculateSum() {
        if (isNaN(parseInt(this.$itemCargoIdInput.val())) && !this.isAddWithoutCalculate) {
            this.setPrice(0);
            this.disabledAllPanels();
            return;
        }

        this.enabledAllPanels();

        if (isNaN(this.$itemCargoIdInput.val())) {
            return;
        }

        let multiple, pricePerEntity, price;

        if (!this.need_volume_and_weight) {
            if (this.need_cube_meter) {
                multiple = this.rangeVolume.getValue();
                pricePerEntity =parseFloat(this.costPerCubeMeter) + parseFloat(this.routeCostPerKilo);
            } else {
                multiple = this.rangeWeight.getValue();
                if (multiple < this.CONST_MIN_WEIGHT) {
                    multiple = this.CONST_MIN_WEIGHT;
                }
                pricePerEntity = parseFloat(this.costPerKilo) + parseFloat(this.routeCostPerKilo);
            }

            multiple = parseFloat(multiple);


            if (isNaN(multiple)) {
                this.setPrice(0);
                return;
            }


            price = multiple * pricePerEntity;
        } else {
            let volume, weight;

            volume = parseFloat(this.rangeVolume.getValue());
            weight = parseFloat(this.rangeWeight.getValue());

            if (weight < this.CONST_MIN_WEIGHT) {
                weight = this.CONST_MIN_WEIGHT;
            }

            if (this.paramPrices.length > 0) {
                let actualPricePerKilo = false,
                    actualPricePerVolume = false;

                for (let i = 0; i < this.paramPrices.length; i++) {
                    if (volume <= parseFloat(this.paramPrices[i]['up_to_volume'])) {
                        actualPricePerVolume = parseFloat(this.paramPrices[i]['volume_price']);
                    }

                    if (weight <= parseFloat(this.paramPrices[i]['up_to_weight'])) {
                        actualPricePerKilo = parseFloat(this.paramPrices[i]['weight_price']);
                    }
                }

                if (actualPricePerKilo === false) {
                    actualPricePerKilo = parseFloat(this.costPerKilo);
                }

                if (actualPricePerVolume === false) {
                    actualPricePerVolume = parseFloat(this.costPerCubeMeter);
                }

                price = weight * (actualPricePerKilo + parseFloat(this.routeCostPerKilo)) +
                    volume * (actualPricePerVolume + parseFloat(this.routeCostPerKilo));
            } else {
                price = weight * (parseFloat(this.costPerKilo) + parseFloat(this.routeCostPerKilo)) +
                    volume * (parseFloat(this.costPerCubeMeter) + parseFloat(this.routeCostPerKilo));
            }
        }

        if (isNaN(price)) {
            this.setPrice(0);
            return;
        }

        this.setPrice(price.toFixed(2));
    }
    setPrice(price) {
        this.$price.text(price);
    }

    sendForm(){
      var obj = self;
      $.post(wp.url, this.getData(), function (data) {
        var self = obj;
        if (data.success == false) {
          var popup = new Popup('#errors');
          popup.setContent(data.data).show();
        }
        else {
          var _popup = new Popup('#success');
          _popup.show();
        }
      });
    }
}
