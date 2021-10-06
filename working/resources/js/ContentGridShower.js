export default class ContentGridShower {

    constructor($elem) {
        this.SHOW_CLASS = 'content-grid__item_show';
        this.CONST_STOP_ANIMATION_RESOLUTION = 719;
        this.currentAbsolutePosition;
        this.currentHeight;
        this.isShowed = false;
        this.position;
        this.$element = $elem;
        this.initStartParam();
        let self = this;
        $(window).bind('scroll', function (ev) {
            self.watchToScroll();
        });
        $(window).bind('resize', function (ev) {
            self.isShowed = false;
            self.initStartParam();
        });
        this.watchToScroll();
    }

    initStartParam() {
        if (!this.isNotMobile()) {
            this.$element.addClass(this.SHOW_CLASS);
            return;
        }

        if (!window.gridItems) {
            window.gridItems = [];
        }

        window.gridItems.push(this);
        this.position = window.gridItems.length - 1;

        this.currentAbsolutePosition = this.$element.offset().top;
        this.currentHeight = this.$element.height();
        this.watchToScroll();
    }

    isNotMobile() {
        if ($(window).width() <= this.CONST_STOP_ANIMATION_RESOLUTION) {
            return false;
        }

        return true;
    }

    watchToScroll() {
        if (!this.isNotMobile()) {
            return;
        }

        if (!this.isShowed) {
            if ($(window).scrollTop() < this.currentAbsolutePosition && $(window).scrollTop() + $(window).height() > this.currentAbsolutePosition) {
                this.isShowed = true;
                let self = this;
                setTimeout(function () {
                    self.$element.addClass(self.SHOW_CLASS);
                }, (this.position % 2) * 100);

            } else {
                if ($(window).scrollTop() > this.currentAbsolutePosition && $(window).scrollTop() + $(window).height() > this.currentAbsolutePosition) {
                    this.isShowed = true;
                    let self = this;
                    setTimeout(function () {
                        self.$element.addClass(self.SHOW_CLASS);
                    }, (this.position % 2) * 100);
                }
            }
        }
    }
}
