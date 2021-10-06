export default class ReCheckTrackNumberController {
    constructor() {
        this.SELCTOR_ELEMENT = '#re_check_tracknumber';
        this.SELECTOR_TRACK_NUMBER = '[name="track_number"]';
        this.SELECTOR_SEND_BTN = '.button_success';
        this.SELECTOR_FORM_GROUP = '.form-group';
        this.CLASS_ERROR = 'has-error';
        this.$element = $(this.SELCTOR_ELEMENT);
        if (this.$element.length == 0) return;
        this.$inputTrackNumber = this.$element.find(this.SELECTOR_TRACK_NUMBER);
        this.$sendBtn = this.$element.find(this.SELECTOR_SEND_BTN);
        let self = this;
        this.$inputTrackNumber.bind('focus', function () {
            if(self.$inputTrackNumber.closest(self.SELECTOR_FORM_GROUP).hasClass(self.CLASS_ERROR)) {
                self.$inputTrackNumber.closest(self.SELECTOR_FORM_GROUP).removeClass(self.CLASS_ERROR);
            }
        });

        this.$sendBtn.bind('click', function (ev) {
            ev.preventDefault();
            ev.stopPropagation();

            if (self.isValid()) {
                self.$element.submit();
            }
        });
    }

    isValid() {
        let
            isValid = true;

        if (this.$inputTrackNumber.val() == '') {
            this.$inputTrackNumber.closest(this.SELECTOR_FORM_GROUP).addClass(this.CLASS_ERROR);
            isValid = false;
        }

        return isValid;
    }
}
