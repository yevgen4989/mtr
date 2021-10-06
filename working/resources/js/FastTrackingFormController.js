import Sender from "./Sender";

export default class FastTrackingFormController {
    constructor() {
        this.SELECTOR_FORM = '.track_cargo';
        this.SELECTOR_INPUT_TRACK_NUMBER = '[name="track_number"]';
        this.SELECTOR_SEND_BTN = '.button_success';
        this.SELECTOR_FORM_GROUP = '.form-group';
        this.CLASS_ERROR = 'has-error';
        this.$trackForm = $(this.SELECTOR_FORM);
        if (this.$trackForm.length == 0) return;
        this.$inputTrackNumber = this.$trackForm.find(this.SELECTOR_INPUT_TRACK_NUMBER);
        this.$btnSend = this.$trackForm.find(this.SELECTOR_SEND_BTN);
        let self = this;
        this.$inputTrackNumber.bind('focus', function () {
            if(self.$inputTrackNumber.closest(self.SELECTOR_FORM_GROUP).hasClass(self.CLASS_ERROR)) {
                self.$inputTrackNumber.closest(self.SELECTOR_FORM_GROUP).removeClass(self.CLASS_ERROR);
            }
        });
        this.$btnSend.bind('click', function (ev) {
            ev.preventDefault();
            ev.stopPropagation();

            if (self.isValid()) {
                self.$trackForm.submit();
            }
            
        });
    }

    isValid() {
        let isValid = true;

        if (this.$inputTrackNumber.val() == '') {
            this.$inputTrackNumber.closest(this.SELECTOR_FORM_GROUP).addClass(this.CLASS_ERROR);
            isValid = false;
        }

        return isValid;
    }
}
