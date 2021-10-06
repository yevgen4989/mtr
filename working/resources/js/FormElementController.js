export default class FormElementController {
    constructor() {
        this.$panels = $(FormElementController.SELECTOR_PANEL_DATA());
        if (this.$panels.length == 0) return;
        this.$formControls = $(FormElementController.SELECTOR_FORM_CONTROL());
        FormElementController.addActionControls(this.$formControls);
        FormElementController.addActionPanel(this.$panels);
    }

    static SELECTOR_PANEL_DATA() {
        return '.panel__data';
    }

    static SELECTOR_FORM_CONTROL() {
        return '.form-control';
    }

    static SELECTOR_SLIDER_INPUT_PLACE() {
        return '.slider__input-place';
    }

    static addActionControls($formControls) {
        if ($formControls === undefined) {
            return;
        }

        $formControls.bind('click', function (ev) {
            ev.preventDefault();
            ev.stopPropagation();
            return true;
        });
    }

    static addActionPanel($panels) {
        if ($panels === undefined) {
            return;
        }
        let self = this;

        $panels.bind('click', function () {
            let $control = $(this).find(self.SELECTOR_FORM_CONTROL());
            if ($control.length > 0) {
                $control.first().trigger('focus');
            } else {
                let $slider = $(this).find(self.SELECTOR_SLIDER_INPUT_PLACE());
                if ($slider.length > 0) {
                    $slider.trigger('click').trigger('focus');
                }
            }
        });
    }
}
