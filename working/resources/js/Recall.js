import Popup from "./Popup";
import Sender from "./Sender";

export default class Recall {
    constructor() {
        this.SELECTOR_RECALL_FORM = '.recall-form';
        this.SELECTOR_RECALL_SEND_BTN = '.button_success';
        this.SELECTOR_RECALL_CLOSE_BTN = '.close';
        this.SELECTOR_RECALL_INPUT_NAME = '[name="name"]';
        this.SELECTOR_RECALL_INPUT_PHONE = '[name="phone"]';
        this.SELECTOR_RECALL_WND = '#recallWnd';
        this.CLASS_RECALL_SHOW = 'recall-form__show';
        this.LINK_TO_SEND_DATA = 'create_recall';
        this.TIME_TO_HIDE_MS = 10000;

        this.$element = $(this.SELECTOR_RECALL_FORM);
        if (this.$element.length === 0) return;

        this.$send = this.$element.find(this.SELECTOR_RECALL_SEND_BTN);
        this.$close = this.$element.find(this.SELECTOR_RECALL_CLOSE_BTN);
        this.$name = this.$element.find(this.SELECTOR_RECALL_INPUT_NAME);
        this.$phone = this.$element.find(this.SELECTOR_RECALL_INPUT_PHONE);
        this.$wnd = new Popup(this.SELECTOR_RECALL_WND);
        this.timeoutId = null;
        this.isClosed = false;

        let self = this;

        this.$send.bind('click', function (ev) {
           ev.stopPropagation();
           ev.preventDefault();
           self.send();
        });

        this.$close.bind('click', function(ev) {
            ev.stopPropagation();
            ev.preventDefault();
            self.isClosed = true;
            self.hide();
        });

        this.$element.bind('click', function () {
            if (self.timeoutId) {
                clearTimeout(self.timeoutId);
            }

        });

        $(window).bind('wheel mousewheel MozMousePixelScroll onmousewheel ', function (ev) {
            let currentScroll = $(this).scrollTop()
            self.scrollWatcher(currentScroll);
        });
        
        $(document).ready(function () {
            self.transitionWatcher();
        })
    }

    scrollWatcher(currentScroll) {
        if (currentScroll + $(window).height() >= $(document).height() &&
            currentScroll > 0 &&
            !this.$element.hasClass(this.CLASS_RECALL_SHOW) &&
            !this.isClosed) {
            if (this.timeoutId) {
                clearTimeout(this.timeoutId);
            }

            let self = this;
            this.timeoutId = setTimeout(function () {
                self.hide();
            }, this.TIME_TO_HIDE_MS);
            this.show();
        }

        if (currentScroll <= $(document).height() / 2) {
            this.isClosed = false;
        }
    }

    transitionWatcher() {
        if (!this.getFromSessionStorage(window.location.href)) {
            this.setToSessionStorage(window.location.href, true);
        } else {
            this.removeFromSessionStorage(window.location.href);
            if (this.timeoutId) {
                clearTimeout(this.timeoutId);
            }

            let
                self = this;
            this.timeoutId = setTimeout(function () {
                self.hide();
            }, this.TIME_TO_HIDE_MS);
            this.show();
        }
    }

    validate() {
        let
            errors = [];

        if (this.$name.val() == '') {
            errors.push('Не указано имя контактного лица');
        }

        if (this.$phone.val() == '') {
            errors.push('Не указан контактный номер телефона');
        }

        if (errors.length !== 0) {
            this.$wnd.setTitle('Ошибка отправки формы');
            let
                errMsg = '';
            for (let i = 0; i < errors.length; i++) {
                errMsg += '<li>' + errors[i] + '</li>';
            }

            this.$wnd.setContent('<ul>' + errMsg + '</ul>').show();
            return false;
        }

        return true;
    }

    getData() {
        let data = {};
        data.action = this.LINK_TO_SEND_DATA;
        data.name = this.$name.val();
        data.phone = this.$phone.val();
        data.comment = 'Заявка на обратный звонок создана со страницы: ' + window.location.href;

        return data;
    }

    send() {
        if (this.validate()) {
            let obj = this;

            $.post(wp.url, this.getData(), function (data) {
              var self = obj;
              if (data.success == false) {
                self.$wnd.setTitle('Ошибка оформления заявки');
                self.$wnd.setContent(data.data).show();
              }
              else {
                self.$wnd.setTitle('Заявка успешно оформлена');
                self.$wnd.setContent(data.data).show();
              }
            });
        }
    }

    show() {
        this.$element.addClass(this.CLASS_RECALL_SHOW);
    }

    hide() {
        this.$element.removeClass(this.CLASS_RECALL_SHOW);
    }

    setToSessionStorage(key, value) {
        window.sessionStorage.setItem(key, value);
    }

    getFromSessionStorage(key) {
        return window.sessionStorage.getItem(key);
    }

    removeFromSessionStorage(key) {
        window.sessionStorage.removeItem(key);
    }
}
