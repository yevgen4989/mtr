export default class Range {
    constructor (selector, min_amount, max_amount, coefficientRanges, non_slide_max_amount, callbackIfMoreMax, showRange = true, callbackIfLessMin) {
        this.SELECTOR_INPUT             = '.slider__input';
        this.SELECTOR_SLIDER            = '.slider__toggle';
        this.SELECTOR_SLIDER_PROGRESS   = '.slider__toggle__background__selected';
        this.SELECTOR_SLIDER_PIN        = '.slider__toggle__pin';
        this.SELECTOR_SLIDER_INPUT_PLACE = '.slider__input-place';

        if (min_amount === undefined) {
            min_amount = 0;
        }

        this.maxAmount      = max_amount;
        this.minAmount      = min_amount;
        this.currentValue   = this.minAmount;
        this.nonSlideMaxAmount = non_slide_max_amount;

        if ((typeof callbackIfMoreMax) === 'function') {
            this.callbackIfMoreMax = callbackIfMoreMax;
        } else {
            this.callbackIfMoreMax = false;
        }

        if ((typeof callbackIfLessMin) === 'function') {
            this.callbackIfLessMin = callbackIfLessMin;
        } else {
            this.callbackIfLessMin = false;
        }

        //Коэффциенты преобразования, является массивом массива трех параметров: минимального значения, максимального значения, коэффициента приращения
        this.coefficientRanges = coefficientRanges === undefined ? false : coefficientRanges;

        this.$element = $(selector);
        this.$input = this.$element.find(this.SELECTOR_INPUT);
        this.$slider = this.$element.find(this.SELECTOR_SLIDER);
        this.$sliderPin         = this.$slider.find(this.SELECTOR_SLIDER_PIN);
        this.$sliderProgress    = this.$slider.find(this.SELECTOR_SLIDER_PROGRESS);
        this.$inputPlace = this.$element.find(this.SELECTOR_SLIDER_INPUT_PLACE)
        if (showRange === false) {
            this.$slider.css({
                'display': 'none',
            });
        }
        if (this.$input.length != 0) {
            this.$input.val(this.currentValue);
        }
        else {
            this.$inputPlace.text(this.currentValue);
        }
        this.sliderWidth            = 0;
        this.sliderPinWidth         = 0;
        this.sliderPinCurPosX       = 0;
        this.mouseStartX    = 0;
        this.mouseEndX      = 0;
        this.mouseMove      = false;
        this.stopInitial    = false;
        this.stopValueInput = false;

        this.initParams();

        let self = this;
        this.$sliderPin.bind('mousedown', function(ev) {
            self.initParams();
            self.mouseStartX    = ev.pageX;
            self.mouseMove      = true;
        });

        this.$sliderPin.bind('touchstart', function (ev) {
            self.initParams();
            self.mouseStartX    = ev.touches[0].pageX;
            self.mouseMove      = true;
        });

        $(window).bind('mousemove', function (ev) {
            if (self.mouseMove) {
                self.mouseEndX  = ev.pageX;
                self.recalculatePinPos();
            }
        });

        $(window).bind('touchmove', function (ev) {
            if (self.mouseMove) {
                self.mouseEndX  = ev.touches[0].pageX;
                self.recalculatePinPos();
            }
        });

        $(window).bind('mouseup', function (ev) {
            if (self.mouseMove) {
                self.mouseMove = false;
                self.mouseEndX = ev.pageX;
                self.recalculatePinPos();
            }
        });

        $(window).bind('touchend', function (ev) {
            if (self.mouseMove) {
                self.mouseMove = false;
                self.mouseEndX = ev.touches.length > 0 ? ev.touches[0].pageX : self.mouseEndX;
                self.recalculatePinPos();
            }
        });

        this.$input.bind('change', function () {
            let value = $(this).val();
            value = parseInt(value);

            if (isNaN(value)) {
                $(this).val(self.minAmount);
            }

            if (value < self.minAmount) {
                if (this.callbackIfLessMin) {
                    this.callbackIfLessMin();
                }
                $(this).val(self.minAmount);
            }

            if(!self.nonSlideMaxAmount) {
                if (value > self.maxAmount) {
                    $(this).val(self.maxAmount);
                }
            } else {
                if (value > self.nonSlideMaxAmount) {
                    $(this).val(self.nonSlideMaxAmount);
                }
            }

            self.recalculateRevert();
        });

        this.$inputPlace.bind('click', function (ev) {
            ev.stopPropagation();
            ev.preventDefault();

            $(this).prop('contenteditable', true);
        });

        this.$inputPlace.bind('focusout', function (ev) {
            let value = $(this).text();
            value = parseInt(value);

            if (isNaN(value)) {
                $(this).text(self.minAmount);
            }

            if (value < self.minAmount) {
                if (self.callbackIfLessMin) {
                    self.callbackIfLessMin();
                }
                $(this).text(self.minAmount);
            }

            if (!self.nonSlideMaxAmount) {
                if (value > self.maxAmount) {
                    $(this).text(self.maxAmount);
                }
            } else {
                if (value > self.nonSlideMaxAmount) {
                    $(this).text(self.nonSlideMaxAmount);
                }
            }

            self.recalculateRevert();
        });

    }

    recalculatePinPos() {
        this.initParams();

        let length = this.mouseEndX - this.mouseStartX;

        this.sliderPinCurPosX += length;

        if (this.sliderPinCurPosX < 0) {
            this.sliderPinCurPosX = 0;
        }

        if ((this.sliderPinCurPosX + this.sliderPinWidth) > this.sliderWidth) {
            this.sliderPinCurPosX = this.sliderWidth - this.sliderPinWidth;
        }

        this.$sliderPin.css('left', this.sliderPinCurPosX);
        this.$sliderProgress.css('left', this.sliderPinCurPosX);
        this.mouseStartX = this.mouseEndX;

        this.recalculateAmount();
    }

    recalculateAmount() {
        let inPercent = this.sliderWidth / 100;
        let currentPercent = 0;
        if (inPercent > 0) {
            currentPercent = this.sliderPinCurPosX / inPercent;
        }

        if ((Math.round(this.sliderPinCurPosX + this.sliderPinWidth)) >= this.sliderWidth) {
            currentPercent = 100;
        }

        if (this.sliderPinCurPosX <= 0) {
            currentPercent = 0;
        }

        let
            amountScale = this.maxAmount - this.minAmount;

        let
            amountScaleInPercent = (amountScale / 100) * currentPercent;

        if (this.coefficientRanges) {
            for (let i = 0; i < this.coefficientRanges.length; i++) {
                let
                    tmpScale = amountScaleInPercent * this.coefficientRanges[i].coefficient + this.minAmount;

                if (tmpScale >= this.coefficientRanges[i].min &&
                    tmpScale <= this.coefficientRanges[i].max) {
                    amountScaleInPercent = amountScaleInPercent * this.coefficientRanges[i].coefficient;
                    break;
                }
            }
        }

        if (!this.stopValueInput) {
            let
                showedValue = Math.round(this.minAmount + amountScaleInPercent);

            if (this.nonSlideMaxAmount) {
                if (showedValue == this.maxAmount) {
                    if (this.callbackIfMoreMax) {
                        this.callbackIfMoreMax();
                    }
                }
            }

            if (this.$input.length > 0) {
                this.$input.val(showedValue);
            } else {
                this.$inputPlace.text(showedValue);
            }
        } else {
            this.stopValueInput = false;
        }

        this.$input.trigger('rangeChange');
        this.$inputPlace.trigger('rangeChange');
    }

    recalculateRevert() {
        let currentValue = 0;

        if (this.$input.length > 0) {
            currentValue = this.$input.val();
        } else {
            currentValue = parseInt(this.$inputPlace.text());
        }

        if (this.nonSlideMaxAmount) {
            if (currentValue > this.maxAmount) {
                currentValue = this.maxAmount;
            }
        }

        let
            amountScale = this.maxAmount - this.minAmount;
        let
            inAmountScalePercent = amountScale / 100;

        let percent = currentValue / inAmountScalePercent;

        if (this.coefficientRanges) {
            for (let i = this.coefficientRanges.length - 1; i >= 0; i--) {
                let
                    tmpCurrentValue = currentValue;

                if (tmpCurrentValue >= this.coefficientRanges[i].min &&
                    tmpCurrentValue <= this.coefficientRanges[i].max) {
                    percent = (tmpCurrentValue / this.coefficientRanges[i].coefficient) / inAmountScalePercent;
                    percent = parseInt(percent);

                    let
                        tmpScaleInPercent;

                    do {
                        for (let j = 0; j < this.coefficientRanges.length; j++) {
                            tmpScaleInPercent = (amountScale / 100) * percent * this.coefficientRanges[j].coefficient  + this.minAmount;
                            tmpScaleInPercent = parseInt(Math.round(tmpScaleInPercent));
                            console.log('tmpScaleInPercentBefore' + tmpScaleInPercent);

                            if (tmpScaleInPercent >= this.coefficientRanges[j].min &&
                                tmpScaleInPercent <= this.coefficientRanges[j].max) {
                                break;
                            }
                        }

                        percent++;
                    } while (tmpScaleInPercent <= currentValue);

                    break;
                }
            }
        }

        let inPercent = this.sliderWidth / 100;
        let newPosX = percent * inPercent;

        this.mouseEndX = Math.round(newPosX - this.sliderPinCurPosX);
        this.mouseStartX = 0;
        this.stopInitial = true;
        this.stopValueInput = true;

        this.recalculatePinPos();
    }


    initParams() {
        if (!this.stopInitial) {
            this.sliderWidth        = this.$slider.width();
            this.sliderPinWidth     = this.$sliderPin.width();
            this.sliderPinCurPosX   = this.$sliderPin.position().left;
            this.sliderProgressCurPosX = this.$sliderProgress.position().left;
        } else {
            this.stopInitial = false;
        }
    }

    getValue() {
        let value = 0;
        if (this.$input.length > 0) {
            return this.$input.val();
        }

        return parseInt(this.$inputPlace.text());
    }

    setValue(value) {
        if (this.maxAmount >= value && value >= this.minAmount) {
            this.currentValue = parseInt(value);
            this.$inputPlace.text(this.currentValue);
            this.recalculateRevert();
        }
    }

    setToDefault() {
        this.currentValue = this.minAmount;
        this.recalculatePinPos();
    }
}
