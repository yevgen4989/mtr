export default class FreeWindow {
    constructor(selectorBindElement, selectorWindow) {
        this.CLASS_SHOW = 'free-window_show';
        this.MARGIN_BIND_ELEMENT = 10;
        this.element = document.querySelectorAll(selectorWindow);
        this.bindBtn = document.querySelectorAll(selectorBindElement);
        if (this.element.length == 0) return;
        if (this.bindBtn.length == 0) return;
        this.element = this.element.item(0);
        this.bindBtn = this.bindBtn.item(0);
        let self = this;
        window.addEventListener('click', function (ev) {
            if (self.element.classList.contains(self.CLASS_SHOW)) {
                let inside = false;
                if (!ev.path) {
                    return;
                } 
                
                for(let i = 0; i < ev.path.length; i++) {
                    if (ev.path[i].classList && ev.path[i].classList.contains(self.CLASS_SHOW)) {
                        inside = true;
                        break;
                    }
                }

                if (!inside) {
                    self.hide();
                }
            }
        });

        window.addEventListener('resize', function (ev) {
            if (self.element.classList.contains(self.CLASS_SHOW)) {
                self.computedPosition();
            }
        });

        this.bindBtn.addEventListener('click', function (ev) {
            ev.stopPropagation();
            ev.preventDefault();
            
            if (self.element.classList.contains(self.CLASS_SHOW)) {
                self.hide();
            } else {
                self.show();
            }
        });
    }

    initBindElemParam() {
        let bindCoord = this.bindBtn.getBoundingClientRect();
        this.positionX = bindCoord.left;
        this.positionY = bindCoord.top;
        this.bindWidth = parseFloat(getComputedStyle(this.bindBtn, null).width.replace("px", ""));
        this.bindHeight = parseFloat(getComputedStyle(this.bindBtn, null).height.replace("px", ""));
        this.windowWidth = parseFloat(getComputedStyle(this.element, null).width.replace('px', ''));
        this.documentWidth = document.documentElement.clientWidth;
        this.PADDING_BODY = parseFloat(getComputedStyle(document.body, null).paddingLeft.replace('px', ''));
    }

    computedPosition() {
        this.initBindElemParam();

        let positionXStart = this.positionX,
            positionYStart = this.positionY,
            positionYEnd = positionYStart + this.bindHeight;

        let positionY = positionYEnd + this.MARGIN_BIND_ELEMENT,
            positionX = positionXStart + ((this.bindWidth - this.windowWidth) / 2);

        //Проверим, вмещается ли наше окно в экран с текущими позициями, и если нет то откорректируем
        if(positionX + this.windowWidth > this.documentWidth) {
            let coeff = positionX + this.windowWidth - this.documentWidth;
            positionX = positionX - (coeff + this.PADDING_BODY);
        }
        
        this.element.style.top = positionY + 'px';
        this.element.style.left = positionX + 'px';
    }

    show() {
        this.computedPosition();
        this.element.classList.add(this.CLASS_SHOW);
    }

    hide() {
        this.element.classList.remove(this.CLASS_SHOW);
    }
}   
