export default class FixerPosition {
    constructor(selector) {
        this.CLASS_FIXED = 'fixed';
        this.CLASS_ABSOLUTE = 'absolute-bottom';

        this.$element = $(selector);
        this.isFixed = false;

        if (this.$element.length == 0) {
            return;
        }

        this.currentTop = this.$element.offset().top;
        this.heightElement = this.$element.outerHeight();
        this.parentHeight = this.$element.parent().height() + this.$element.parent().offset().top;
        this.initScale();
        this.watchForFixed($(window).scrollTop());
        let self = this;
        $(window).bind('resize', function () {
            self.initScale();
        });
        $(window).bind('scroll', function () {
            self.watchForFixed($(window).scrollTop());
        });
    }

    initScale() {
        this.removeAbsolute();
        if (this.isFixed) {
            this.removeFixed();
        }
        this.currentTop = this.$element.offset().top;
    }

    watchForFixed(scrollTop) {
        if (scrollTop >= this.currentTop && scrollTop <= (this.parentHeight - this.heightElement)) {
            if (!this.isFixed) {
                if (scrollTop <= (this.parentHeight - this.heightElement)) {
                    this.removeAbsolute();
                } else {
                    this.setFixed();
                }
            }
        } else {
            if (this.isFixed) {
                if (scrollTop > (this.parentHeight - this.heightElement)) {
                    this.setAbsolute();
                } else {
                    this.removeFixed();
                }
            }
        }
    }

    setFixed() {
        this.$element.addClass(this.CLASS_FIXED);
        this.isFixed = true;
    }

    removeFixed() {
        this.$element.removeClass(this.CLASS_FIXED);
        this.isFixed = false;
    }

    setAbsolute() {
        this.removeFixed();
        this.$element.addClass(this.CLASS_ABSOLUTE);
    }

    removeAbsolute() {
        this.$element.removeClass(this.CLASS_ABSOLUTE);
        this.setFixed();
    }
}
