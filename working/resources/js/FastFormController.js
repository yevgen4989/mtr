export default class FastFormCalculator {
    constructor() {
        this.SELECTOR_CALCULATOR_FORM = '.fast-calculate';
        this.SELECTOR_CALCULATOR_RESULT_FORM = '.fast-result';
        this.SELECTOR_COUNTRY = '[name="country"]';
        this.SELECTOR_CARGO = '[name="type_cargo"]';
        this.SELECTOR_WEIGHT = '[name="weight"]';
        this.SELECTOR_CONTACT = '[name="contact"]';
        this.SELECTOR_BUTTON = '.button';
        this.SELECTOR_SUM = 'span';
        this.SELECTOR_FORM_GROUP = '.form-group';
        this.ACTION_TO_CALCULATE = 'calculate_create';
        this.CLASS_ERROR = 'has-error';
        this.$calculateForm = $(this.SELECTOR_CALCULATOR_FORM);
        this.$resultForm = $(this.SELECTOR_CALCULATOR_RESULT_FORM);
        if (this.$calculateForm.length == 0) return;
        this.$sum = this.$resultForm.find(this.SELECTOR_SUM);
        this.$btnBack = this.$resultForm.find(this.SELECTOR_BUTTON);
        this.$country = this.$calculateForm.find(this.SELECTOR_COUNTRY);
        this.$typeCargo = this.$calculateForm.find(this.SELECTOR_CARGO);
        this.$weight = this.$calculateForm.find(this.SELECTOR_WEIGHT);
        this.$contact = this.$calculateForm.find(this.SELECTOR_CONTACT);
        this.$btnSend = this.$calculateForm.find(this.SELECTOR_BUTTON);

        let self = this;

        this.$country.bind('focus', function () {
            if (self.$country.closest(self.SELECTOR_FORM_GROUP).hasClass(self.CLASS_ERROR)) {
                self.$country.closest(self.SELECTOR_FORM_GROUP).removeClass(self.CLASS_ERROR);
            }
        });

        this.$typeCargo.bind('focus', function () {
            if(self.$typeCargo.closest(self.SELECTOR_FORM_GROUP).hasClass(self.CLASS_ERROR)) {
                self.$typeCargo.closest(self.SELECTOR_FORM_GROUP).removeClass(self.CLASS_ERROR);
            }
        });

        this.$weight.bind('focus', function () {
            if(self.$weight.closest(self.SELECTOR_FORM_GROUP).hasClass(self.CLASS_ERROR)) {
                self.$weight.closest(self.SELECTOR_FORM_GROUP).removeClass(self.CLASS_ERROR);
            }
        });

        this.$contact.bind('focus', function () {
            if(self.$contact.closest(self.SELECTOR_FORM_GROUP).hasClass(self.CLASS_ERROR)) {
                self.$contact.closest(self.SELECTOR_FORM_GROUP).removeClass(self.CLASS_ERROR);
            }
        });


        this.$btnSend.bind('click', function (ev) {
            ev.preventDefault();
            ev.stopPropagation();
            self.sendToCalculate();
        });

        this.$btnBack.bind('click', function (ev) {
            ev.preventDefault();
            ev.stopPropagation();
            self.clearCalculateForm();
            self.hideResultForm();
            self.showCalculateForm();
        })
    }

    clearCalculateForm() {
        this.$typeCargo.val('');
        this.$weight.val('');
        this.$contact.val('');
    }

    isValid() {
        let isValid = true;

        function validateEmail(email) {
          var re = /[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?/;
          return re.test(String(email).toLowerCase());
        }

        function validatePhone(phone){
          let regex = /^(\+7|7|8)?[\s\-]?\(?[489][0-9]{2}\)?[\s\-]?[0-9]{3}[\s\-]?[0-9]{2}[\s\-]?[0-9]{2}$/;
          return regex.test(phone);
        }

        if (this.$country.val() == '') {
            this.$country.closest(this.SELECTOR_FORM_GROUP).addClass(this.CLASS_ERROR);
            isValid = false;
        }

        if (this.$typeCargo.val() == '') {
            this.$typeCargo.closest(this.SELECTOR_FORM_GROUP).addClass(this.CLASS_ERROR);
            isValid = false;
        }

        if (this.$weight.val() == '' || isNaN(parseInt(this.$weight.val()))) {
            this.$weight.closest(this.SELECTOR_FORM_GROUP).addClass(this.CLASS_ERROR);
            isValid = false;
        }

        if (this.$contact.val() == '') {
            this.$contact.closest(this.SELECTOR_FORM_GROUP).addClass(this.CLASS_ERROR);
        }

        if (this.$contact.val().indexOf('@') === 0) {
          if(!validateEmail(this.$contact.val())){
            this.$contact.closest(this.SELECTOR_FORM_GROUP).addClass(this.CLASS_ERROR);
          }
        }
        else {
          if (isNaN(parseInt(this.$contact.val())) && !validatePhone(this.$contact.val())) {
            this.$contact.closest(this.SELECTOR_FORM_GROUP).addClass(this.CLASS_ERROR);
          }
        }

        return isValid;
    }

    getDataCalculateForm() {
        let data = {};
        data['action'] = this.ACTION_TO_CALCULATE;
        data['country'] = this.$country.val();
        data['typeCargo'] = this.$typeCargo.val();
        data['weight'] = this.$weight.val();
        data['contact'] = this.$contact.val();
        return data;
    }

    sendToCalculate() {
        if (this.isValid()) {
          let self = this;
          $.post(wp.url, this.getDataCalculateForm(), function (data) {
            self.handleResponse(data);
          });
        }
    }

    handleResponse(data) {
      if (data && data.success == true) {
        this.hideCalculateForm();
        this.$sum.text(data.data.price);
        this.showResultForm();
      }
      if (data && data.success == false) {}
    }


    hideCalculateForm() {
        this.$calculateForm.css({
            'display': 'none',
        });
    }

    showCalculateForm() {
        this.$calculateForm.css({
            'display': 'block',
        });
    }

    hideResultForm() {
        this.$resultForm.css({
            'display': 'none',
        });
    }

    showResultForm() {
        this.$resultForm.css({
            'display': 'block',
        });
    }
}
