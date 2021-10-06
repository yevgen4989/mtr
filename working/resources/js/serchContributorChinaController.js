import Popup from "./Popup";
import Sender from "./Sender";
import FreeWindow from "./FreeWindow";

export default class searchContributorChinaController {
    constructor() {
        this.BIG_FORM_1_SELECTOR = '#form_1';
        this.BIG_FORM_2_SELECTOR = '#form_2';
        this.SMALL_FORM_1_SELECTOR = '#form_3';
        this.SMALL_FORM_2_SELECTOR = '#form_4';
        this.SMALL_FORM_3_SELECTOR = '#form_5';
        this.SELECTOR_FREE_WINDOW = '#formRecall';
        this.SELECTOR_RECALL_BTN = '.list__item .button';


        new BigForm(this.BIG_FORM_1_SELECTOR);
        new BigForm(this.BIG_FORM_2_SELECTOR);
        new SmallForm(this.SMALL_FORM_1_SELECTOR);
        new SmallForm(this.SMALL_FORM_2_SELECTOR);
        new SmallForm(this.SMALL_FORM_3_SELECTOR);
        new FreeWindow(this.SELECTOR_RECALL_BTN, this.SELECTOR_FREE_WINDOW);
    }
}

class BigForm {
    constructor(selector) {
        this.SELECTOR_GOOD_NAME = '[name="good_name"]';
        this.SELECTOR_GOOD_AMOUNT = '[name="good_amount"]';
        this.SELECTOR_CUSTOMER_NAME = '[name="customer_name"]';
        this.SELECTOR_CUSTOMER_CONTACT = '[name="customer_contact"]';
        this.SELECTOR_CITY_TO = '[name="city_to"]';
        this.SELECTOR_MANUFACTURE = '[name="manufacture"]';
        this.SELECTOR_COMMENT = '[name="comment"]';
        this.SELECTOR_SEND_BTN = '.button';
        this.CLASS_ERROR = 'has-error';
        this.SELECTOR_MESSAGE_WINDOW = '#message';
        this.SELECTOR_INFO_WINDOW = '#info';
        this.PATH_TO_CREATE = '/search/create';
        this.element = document.querySelectorAll(selector);
        if (this.element.length == 0) return;
        this.element = this.element.item(0);
        this.inputGoodName = this.element.querySelectorAll(this.SELECTOR_GOOD_NAME).item(0);
        this.inputGoodAmount = this.element.querySelectorAll(this.SELECTOR_GOOD_AMOUNT).item(0);
        this.inputCustomerName = this.element.querySelectorAll(this.SELECTOR_CUSTOMER_NAME).item(0);
        this.inputCustomerContact = this.element.querySelectorAll(this.SELECTOR_CUSTOMER_CONTACT).item(0);
        this.inputCityTo = this.element.querySelectorAll(this.SELECTOR_CITY_TO).item(0);
        this.inputManufacture = this.element.querySelectorAll(this.SELECTOR_MANUFACTURE).item(0);
        this.inputComment = this.element.querySelectorAll(this.SELECTOR_COMMENT).item(0);
        this.buttonSubmit = this.element.querySelectorAll(this.SELECTOR_SEND_BTN).item(0);
        this.window = new Popup(this.SELECTOR_MESSAGE_WINDOW);
        this.infoWindow = new Popup(this.SELECTOR_INFO_WINDOW);
        this.isSubmit = false;
        let self = this;
        this.buttonSubmit.addEventListener('click',  function (ev) {
            ev.preventDefault();
            ev.stopPropagation();
            self.submit();
        });
    }

    isValid() {
        let 
            valid = true,
            message = [];

        if (this.inputGoodName.value == '') {
            valid = false;
            this.setErrorClass(this.inputGoodName);
            message.push('Наименование товара');
        }

        if (this.inputGoodAmount.value == '') {
            valid = false;
            this.setErrorClass(this.inputGoodAmount);
            message.push('Количество единиц груза');
        }

        if (this.inputCityTo.value == '') {
            valid = false;
            this.setErrorClass(this.inputCityTo);
            message.push('Город назначения');
        }

        if (this.inputCustomerContact.value == '') {
            valid = false;
            this.setErrorClass(this.inputCustomerContact);
            message.push('Email или телефон');
        }

        //Проверка на тип данных
        if (message.length > 0) {
            let 
                content = '';
            for(let i = 0; i < message.length; i++) {
                content += message[i] + '<br>';
            }
            this.window.setTitle('Введены неверные данные').setContent(content).show();
        }
        

        return valid;
    }

    setErrorClass(item) {
        item.parentNode.classList.add(this.CLASS_ERROR);
        let 
            self = this;
        item.addEventListener('focus', function (ev) {
            item.parentNode.classList.remove(self.CLASS_ERROR);
        });
    }

    getData() {
        let data = {};
        data.good_name = this.inputGoodName.value;
        data.good_amount = this.inputGoodAmount.value;
        data.customer_name = this.inputCustomerName.value;
        data.customer_contact = this.inputCustomerContact.value;
        data.city_to = this.inputCityTo.value;
        data.manufacture = this.inputManufacture.value;
        data.comment = this.inputComment.value;

        return data;
    }

    submit() {
        if (this.isValid() && !this.isSubmit) {
            this.isSubmit = true;
            let 
                sender = new Sender(this.getData(), this.handleSubmit.bind(this), this.PATH_TO_CREATE);
            sender.send();
            this.infoWindow.setTitle('Обработка данных').setContent('Отправка данных.').setNoClose(true).show();
        }
    }

    clearFields() {
        this.inputGoodName.value = '';
        this.inputGoodAmount.value = '';
        this.inputCustomerName.value = '';
        this.inputCustomerContact.value = '';
        this.inputCityTo.value = '';
        this.inputManufacture.value = '';
        this.inputComment.value = '';
    }

    handleSubmit(data) {
        data.json().then((value) => {
            this.infoWindow.hide();
            if (value.status) {
                this.window.setTitle('Заявка успешно отправлена').setContent('Ваша заявка успешно отправлена, в ближайшее время с вами свяжется наш менеджер для уточнения информации.').show(); 
             } else {
                let 
                    message = '';
                for(let i = 0; i < value.message.length; i++) {
                    message += value.message[i] + '<br>'
                }
                this.window.setTitle('Ошибка отправки заявки').setContent(message).show();
             }
            this.isSubmit = false;
            this.clearFields();
        });
    }
}

class SmallForm extends BigForm {
    constructor(selector) {
        super(selector);
        this.PATH_TO_CREATE = '/recall/create';
    }

    isValid() {
        let 
            valid = true,
            message = [];

        if (this.inputCustomerName.value == '') {
            valid = false;
            this.setErrorClass(this.inputCustomerName);
            message.push('Ваше имя');
        }

        if (this.inputCustomerContact.value == '') {
            valid = false;
            this.setErrorClass(this.inputCustomerContact);
            message.push('Email или телефон');
        }

        //Проверка на тип данных
        if (message.length > 0) {
            let 
                content = '';
            for(let i = 0; i < message.length; i++) {
                content += message[i] + '<br>';
            }
            this.window.setTitle('Введены неверные данные').setContent(content).show();
        }
        

        return valid;
    }

    getData() {
        let data = {};
        data.name = this.inputCustomerName.value;
        data.phone = this.inputCustomerContact.value;
        data.comment = 'Заявка на консультацию по вопросам поиска товара или поставщика в Китае.';

        return data;
    }

    clearFields() {
        this.inputCustomerName.value = '';
        this.inputCustomerContact.value = '';
    }

    handleSubmit(data) {
        data.json().then((value) => {
            this.infoWindow.hide();
            if (value.status == 'success') {
                this.window.setTitle('Заявка успешно отправлена').setContent('Ваша заявка успешно отправлена, в ближайшее время с вами свяжутся наши менеджеры.').show(); 
             } else {
                this.window.setTitle('Ошибка отправки данных').setContent(value.message).show();
             }
            this.isSubmit = false;
            this.clearFields();
        });
    }
}
