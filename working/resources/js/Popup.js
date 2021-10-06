export default class Popup {
    constructor(selector, noClose = false) {
        let self = this;

        this.CLASS_ACTIVE           = 'window_active';
        this.CLASS_MATERIALIZED     = 'window_materialized';
        this.SELECTOR_BODY          = '.window__body';
        this.SELECTOR_TITLE         = '.window__body__title';
        this.SELECTOR_CONTENT       = '.window__body__content';
        this.SELECTOR_CLOSE_BUTTON  = '.window__close';
        this.CLASS_STOP_SCROLL      = 'stop-scroll';
        this.ANIMATION_TIME_MSEC    = 800;
        this.isNoClose = noClose;
        this.isClose = true;
        this.$element   = $(selector);
        this.$body      = this.$element.find(this.SELECTOR_BODY);
        this.$title     = this.$element.find(this.SELECTOR_TITLE);
        this.$content   = this.$element.find(this.SELECTOR_CONTENT);
        this.$closeBtn  = this.$body.find(this.SELECTOR_CLOSE_BUTTON);
        this.$body.bind('click', function (ev) {
            if (ev.target.localName === 'a') {
                return true;
            }
            ev.stopPropagation();
            ev.preventDefault();
        });
        this.$element.bind('click', function (ev) {
            if (ev.target.localName === 'a') {
                return true;
            }
            ev.stopPropagation();
            ev.preventDefault();
            if (!self.isNoClose) {
                self.hide();
            }
        });

        this.$closeBtn.bind('click', function (ev) {
            ev.stopPropagation();
            ev.preventDefault();
            if (!self.isNoClose) {
                self.hide();
            }
        });

        $(window).bind('keydown', function (ev) {
            if (ev.keyCode == 27 && !self.isClose) {
                self.hide();
            }
        });
    }

    /**
     * Устанавливает соответствующий стиль для возможности плавного открытия
     */
    preOpen() {
        this.$element.addClass(this.CLASS_MATERIALIZED);
    }

    _open() {
        this.$element.addClass(this.CLASS_ACTIVE);
        this.isClose = false;
    }

    /**
     * Убирает соответствующий стиль у окна после закрытия и отработки эффекта
     */
    postClose() {
        this.$element.removeClass(this.CLASS_MATERIALIZED);
    }

    _close() {
        this.$element.removeClass(this.CLASS_ACTIVE);
        this.isClose = true;
    }

    getBody() {
        return this.$body;
    }

    setTitle(title) {
        this.$title.html(title);
        return this;
    }

    setNoClose(noClose) {
        this.isNoClose = noClose;
        return this;
    }

    setContent(content) {
        if (content.prototype) {
            this.$content.append(content);
        } else {
            this.$content.html(content);
        }
        return this;
    }

    _setStopScrollBody() {
        $('body').addClass(this.CLASS_STOP_SCROLL);
    }

    _removeStopScrollBody() {
        $('body').removeClass(this.CLASS_STOP_SCROLL);
    }

    /**
     * Отображает окно
     */
    show() {
        this._setStopScrollBody();
        this.preOpen();
        let self = this;
        setTimeout(function () {
            self._open();
        }, 10);
    }

    /**
     * Скрывает окно
     */
    hide() {
        this._removeStopScrollBody();
        this._close();
        let self = this;
        setTimeout(function () {
            self.postClose();
        }, this.ANIMATION_TIME_MSEC);
    }
}
