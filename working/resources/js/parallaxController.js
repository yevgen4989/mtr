export default class parallaxController {
    constructor() {
        this.SELECTOR_PARALLAX = '.parallax-background';
        this.COEFFICIENT = 0.3;

        this.element = document.querySelectorAll(this.SELECTOR_PARALLAX);
        if (this.element.length === 0) return;
        this.element = this.element.item(0);

        let 
            self = this;

        document.addEventListener('scroll', function (ev) {
            self.render();
        });
    }

    render() {
        let 
            scrollTop = window.pageYOffset;

        this.element.style.top = (-1 * scrollTop * this.COEFFICIENT) + 'px';
    }
}