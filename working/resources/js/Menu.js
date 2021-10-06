export default class Menu {
    constructor () {
        this.ELEMENT_SELECTOR = 'header';
        this.MOBILE_MENU_BTN_SELECTOR = '.header__main-line__mobile-menu-line__menu';
        this.MOBILE_MENU_SELECTOR = '.header__menu';
        this.MOBILE_MENU_ACTIVE_CLASS = 'header__menu_active';
        this.MOBILE_MENU_BTN_ACTIVE_CLASS = 'header__main-line__mobile-menu-line__menu_active';
        this.NO_BODY_SCROLL_CLASS = 'stop-scroll';
        this.$element = $(this.ELEMENT_SELECTOR);
        this.$mobileMenuBtn = $(this.MOBILE_MENU_BTN_SELECTOR);
        this.$headerMenu = $(this.MOBILE_MENU_SELECTOR);
        this.$body = $('body');
        this.isActiveMenu = false;
        let self = this;
        this.$mobileMenuBtn.bind('click', function (ev) {
            ev.preventDefault();
            ev.stopPropagation();
            self.toggleMenu();
        });
    }

    toggleMenu() {
        if (this.isActiveMenu) {
            this.isActiveMenu = false;
            this.unsetNoScrollBody();
            this.closeMobileMenu();
            this.menuBtnDisable();
        } else {
            this.isActiveMenu = true;
            this.setNoScrollBody();
            this.openMobileMenu();
            this.menuBtnEnable();
        }
    }

    openMobileMenu() {
        this.$headerMenu.addClass(this.MOBILE_MENU_ACTIVE_CLASS);
    }

    closeMobileMenu() {
        this.$headerMenu.removeClass(this.MOBILE_MENU_ACTIVE_CLASS);
    }

    menuBtnEnable() {
        this.$mobileMenuBtn.addClass(this.MOBILE_MENU_BTN_ACTIVE_CLASS);
    }

    menuBtnDisable() {
        this.$mobileMenuBtn.removeClass(this.MOBILE_MENU_BTN_ACTIVE_CLASS);
    }

    setNoScrollBody() {
        this.$body.addClass(this.NO_BODY_SCROLL_CLASS);
    }

    unsetNoScrollBody() {
        this.$body.removeClass(this.NO_BODY_SCROLL_CLASS);
    }

}
