export default class HintWindow {
    constructor(selector) {
        this.CLASS_SHOW = 'hint-window__show';
        this.$element = $(selector);
    }

    show() {
        this.$element.addClass(this.CLASS_SHOW);
    }

    hide() {
        this.$element.removeClass(this.CLASS_SHOW);
    }
}
