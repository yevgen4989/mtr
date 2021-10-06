const {createApp, h} = Vue;

const
  buttonComponent = {
    props: ['title', 'isGreen', 'isNoMB'],
    template: '<button :class="{app__btn: true, app__btn_green: isGreen, app__btn_nmb: isNoMB}" >{{ title }}</button>'
  },
  inputComponent = {
    props: ['placeholder', 'value', 'hasError', 'errorMessage', 'name'],
    emits: ['update:value'],
    template: `<input :class="{app__input: true, app__input_error: hasError}"
                      :placeholder="!hasError ? placeholder : errorMessage" :name="name" :value="value"
                      autocomplete="off" @input="$emit('update:value', $event)">`,
  },
  textInputComponent = {
    props: ['placeholder', 'value', 'name'],
    emits: ['update:value'],
    template: `<textarea :class="{app__textarea: true}" :placeholder="placeholder" :name="name" autocomplete="off"
                         @input="$emit('update:value', $event)">{{value}}</textarea>`,
  },
  selectorComponent = {
    props: ['items', 'hasError', 'name'],
    template: `
      <select :class="{app__select: true, app__select_error: hasError}" :name="name">
      <option v-for="item in items" :value="item.id">{{ item.name }}</option>
      </select>
    `,
  },
  socialLinkComponent = {
    data() {
      return {
        links: [
          {
            src: '/img/social_icon_vk.svg',
            link: 'https://vk.com/mtrgroupru',
          },
          {
            src: '/img/social_icon_fb.svg',
            link: 'https://www.facebook.com/Mtrgroup-102721134994669',
          },
          {
            src: '/img/social_icon_in.svg',
            link: 'https://www.instagram.com/mtrgroup.ru/',
          }
        ]
      }
    },
    template: `
      <div class="app__sociallinks">
      <a v-for="link in links" :href="link.link" class="app__sociallinks__item">
        <img :src="link.src"/>
      </a>
      </div>
    `,
  },
  ScreenComponentMain = {
    data() {
      return {
        language: {
          'screenTitle': 'MTRGROUP приложение',
          'calculateDeliveryLNG': 'Рассчитать стоимость доставки',
          'searchInChina': 'Поиск товара или поставщика в Китае',
          'call': 'Позвонить',
          'email': 'Написать',
        },

        callLink: 'tel:88005001406',
        emailLink: 'mailto:vopros@mtrgroup.ru',
      }
    },

    mounted() {
      window.document.title = this.language.screenTitle;
    },

    methods: {
      goToCalculateDeliveryPriceScreen() {
        this.$parent.goToScreen('/sociallink/calk_delivery');
      },

      goToSearchCargoAndManufactureScreen() {
        this.$parent.goToScreen('/sociallink/search_in_china');
      },

      call() {
        window.location.href = this.callLink;
      },

      mailTo() {
        window.location.href = this.emailLink;
      }
    },


    template: `
      <div class="app__panel">
      <btn :title="this.language.calculateDeliveryLNG" :isGreen="true" @click="goToCalculateDeliveryPriceScreen"></btn>
      <btn :title="this.language.searchInChina" :isGreen="false" @click="goToSearchCargoAndManufactureScreen"></btn>
      <btn :title="this.language.call" :isGreen="false" @click="call"></btn>
      <btn :title="this.language.email" :isGreen="false" @click="mailTo"></btn>
      <socialLink></socialLink>
      </div>
    `,
  },
  ScreenComponentCalculateDeliveryPrice = {
    data: () => ({
      language: {
        'screenTitle': 'MTRGROUP расчет стоимости доставки',
        'headerGeneral': 'Быстрый расчет',
        'headerExplanation': 'доставки в Россию',
        'load': 'Загрузка',
        'call': 'Позвонить',
        'email': 'Написать',
        'cargoName': 'Что везете?',
        'weightName': 'Вес кг',
        'contactName': 'Телефон или Email',
        'next': 'Далее',
        'back': 'Назад',
        'calculationPrice': 'Стоимость доставки: ',
        'disclaimer': 'Данная ставка не включает в себя стоимость доставки до нашего транзитного склада в ЕС. Не является офертой.',

        'requiredField': 'Обязательное для заполнения поле',
        'errorWeightOnlyNumber': 'Только целы числа от 1',

      },

      callLink: 'tel:88005001406',
      emailLink: 'mailto:vopros@mtrgroup.ru',


      countries: [],
      isCountryError: false,
      isCargoError: false,
      isWeightError: false,
      isContactError: false,
      ErrorWeightMessage: '',
      loadData: true,
      calculateFinish: false,
      calculateError: false,
      calucalteErrorMessage: '',

      countryId: null,
      cargo: '',
      weight: '',
      contact: '',
      calcualtePrice: '',


    }),

    created() {
      this.loadCountries();
    },

    mounted() {
      window.document.title = this.language.screenTitle;
    },

    methods: {
      changeCountry(event) {
        this.countryId = event.target.value;
      },

      validateWeight($event) {
        let
          value = $event.target.value;

        if (value == '') {
          this.weight = '';
          this.isWeightError = false;
          this.ErrorWeightMessage = this.language.requiredField;
        } else {
          if (/^[1-9][0-9]*$/i.test(value)) {
            this.isWeightError = false;
            this.weight = value;
          } else {
            this.isWeightError = true;
            this.ErrorWeightMessage = this.language.errorWeightOnlyNumber;
            $event.target.value = this.weight;
          }
        }
      },

      validate() {
        let
          isError = true;

        if (this.countryId == '' || this.countryId == null) {
          this.isCountryError = true;
          isError = false;
        }

        if (this.cargo == '') {
          this.isCargoError = true;
          isError = false;
        }

        if (this.weight == '') {
          this.isWeightError = true;
          this.ErrorWeightMessage = this.language.requiredField;
          isError = false;
        }

        if (this.contact == '') {
          this.isContactError = true;
          isError = false;
        }

        return isError;
      },

      loadCountries() {
        (new Sender('', (data) => {
          data.json().then((data) => {
            this.countries = data;
            if (this.countries[0] && this.countries[0].id) {
              this.countryId = this.countries[0].id;
            }
            this.loadData = false;
          });
        }, '/api/v1/countries')).sendGet();
      },

      call() {
        window.location.href = this.callLink;
      },

      mailTo() {
        window.location.href = this.emailLink;
      },

      goBack() {
        this.$parent.goToScreen('/sociallink');
      },

      calculate() {
        if (this.validate()) {
          let
            dataRequest = {
              'country_id': this.countryId,
              'cargo': this.cargo,
              'weight': this.weight,
              'contact': this.contact,
            };

          this.loadData = true;
          (new Sender(dataRequest, (data) => {
            data.json().then((data) => {
              this.loadData = false;
              this.calculateFinish = true;
              if (data.status) {
                this.calculateError = false;
                this.calcualtePrice = data.price;
              } else {
                this.calculateError = true;
                this.calucalteErrorMessage = data.message;
              }
            });
          }, '/api/v1/short_calculate')).send();
        }
      },
    },


    template: `
      <div class="app__panel app__panel_white">
      <h2 class="title title__size2 title_no-margin">
        {{ this.language.headerGeneral }}
        <span class="title__explanation">{{ this.language.headerExplanation }}</span>
      </h2>
      <div v-if="!loadData & !calculateFinish && !calculateFinish" class="app__form">
        <selector @change="changeCountry" :hasError="this.isCountryError" :items="countries"
                  @focus="this.isCountryError = false;" name="country"></selector>
        <field :hasError="isCargoError" :errorMessage="this.language.requiredField"
               :placeholder="this.language.cargoName" v-on:input="cargo = $event.target.value"
               @focus="this.isCargoError = false;" v-bind:value="cargo" name="cargo"></field>
        <field :hasError="isWeightError" :errorMessage="ErrorWeightMessage" :placeholder="this.language.weightName"
               :value="weight" @focus="this.isWeightError = false;" @update:value="validateWeight($event)"
               name="weight"></field>
        <field :hasError="isContactError" :errorMessage="this.language.requiredField"
               :placeholder="this.language.contactName" :value="contact" @focus="this.isContactError = false;"
               @update:value="contact = $event.target.value" name="contact"></field>
        <btn :title="this.language.next" :isGreen="true" :isNoMB="true" @click="calculate"></btn>
      </div>
      <div v-if="calculateFinish" class="app__form">
        <span class="app__message" v-if="!calculateError">{{ this.language.calculationPrice }} {{ this.calcualtePrice }}
          $</span>
        <span class="app__message" v-else>{{ this.calucalteErrorMessage }}</span>
        <btn :title="this.language.back" :isGreen="true" :isNoMB="false" @click="goBack"></btn>
        <span class="app__message app__message_info">{{ this.language.disclaimer }}</span>
      </div>
      <div v-if="loadData" class="app__form">{{ this.language.load }}</div>
      </div>
      <btn :title="this.language.call" :isGreen="false" @click="call"></btn>
      <btn :title="this.language.email" :isGreen="false" @click="mailTo"></btn>
      <socialLink></socialLink>
    `,
  },
  ScreenComponentSearchCargoAndManufacture = {
    data: () => ({
      language: {
        screenTitle: 'MTRGROUP поиск товара или поставщика в Китае',
        headerGeneral: 'Поиск',
        headerExplanation: 'товара или поставщика в Китае',

        requiredField: 'Обязательное для заполнения поле',
        cargoName: 'Наименование груза',
        weightName: 'Вес и объем (если известно)',
        manufactureName: 'Фабрика (если известно)',
        cityName: 'Город назначения',
        nameName: 'Ваше имя',
        contactName: 'Контактный телефон или Email',
        commentName: 'Комментарий',

        btnRegisterOrder: 'Оставить заявку',

        call: 'Позвонить',
        email: 'Написать',

        load: 'Загрузка',
        back: 'Назад',

        successMessage: 'Ваша заявка принята, в ближайшее время с вами свяжутся наши специалисты для уточнения деталей поиска.',

      },

      callLink: 'tel:88005001406',
      emailLink: 'mailto:vopros@mtrgroup.ru',

      loadData: false,
      sendedRequest: false,

      cargo: '',
      isCargoError: false,
      weight: '',
      manufacture: '',
      city: '',
      isCityError: false,
      name: '',
      isNameError: false,
      contact: '',
      isContactError: false,
      comment: '',

      successMessage: '',
    }),

    mounted() {
      window.document.title = this.language.screenTitle;
    },

    methods: {
      register() {
        let
          data = {};

        if (this.isValid()) {
          data.cargo = this.cargo;
          data.weight = this.weight;
          data.manufacture = this.manufacture;
          data.city = this.city;
          data.name = this.name;
          data.contact = this.contact;
          data.comment = this.comment;

          this.loadData = true;

          (new Sender(data, (data) => {
            data.json().then((data) => {
              this.loadData = false;
              this.sendedRequest = true;
              if (data.status) {
                this.successMessage = this.language.successMessage;
              } else {
                this.successMessage = data.message;
              }
            });
          }, '/api/v1/china_search')).send();
        }
      },

      call() {
        window.location.href = this.callLink;
      },

      mailTo() {
        window.location.href = this.emailLink;
      },

      goBack() {
        this.$parent.goToScreen('/sociallink');
      },

      isValid() {
        let
          isValid = true;

        if (this.cargo == '') {
          isValid = false;
          this.isCargoError = true;
        }

        if (this.city == '') {
          isValid = false;
          this.isCityError = true;
        }

        if (this.name == '') {
          isValid = false;
          this.isNameError = true;
        }

        if (this.contact == '') {
          isValid = false;
          this.isContactError = true;
        }

        return isValid;
      }

    },


    template: `
      <div class="app__panel app__panel_white">
      <h2 class="title title__size2 title_no-margin">
        {{ this.language.headerGeneral }}
        <span class="title__explanation title__explanation_as-block">{{ this.language.headerExplanation }}</span>
      </h2>
      <div class="app__form" v-if="!loadData && !sendedRequest">
        <field :hasError="isCargoError" :errorMessage="this.language.requiredField"
               :placeholder="this.language.cargoName" v-on:input="cargo = $event.target.value"
               @focus="this.isCargoError = false;" v-bind:value="cargo" name="cargo"></field>
        <field :placeholder="this.language.weightName" v-on:input="weight = $event.target.value" v-bind:value="weight"
               name="weight"></field>
        <field :placeholder="this.language.manufactureName" v-on:input="manufacture = $event.target.value"
               v-bind:value="manufacture" name="manufacture"></field>
        <field :hasError="isCityError" :errorMessage="this.language.requiredField" :placeholder="this.language.cityName"
               v-on:input="city = $event.target.value" @focus="this.isCityError = false;" v-bind:value="city"
               name="city"></field>
        <field :hasError="isNameError" :errorMessage="this.language.requiredField" :placeholder="this.language.nameName"
               v-on:input="name = $event.target.value" @focus="this.isNameError = false;" v-bind:value="name"
               name="name"></field>
        <field :hasError="isContactError" :errorMessage="this.language.requiredField"
               :placeholder="this.language.contactName" v-on:input="contact = $event.target.value"
               @focus="this.isContactError = false;" v-bind:value="contact" name="contact"></field>
        <textField :placeholder="this.language.commentName" v-on:input="comment = $event.target.value"
                   v-bind:value="comment" name="comment"></textField>
        <btn :title="this.language.btnRegisterOrder" :isGreen="true" :isNoMB="true" @click="register"></btn>
      </div>

      <div class="app__form" v-if="loadData">
        <span class="app__message">{{ this.language.load }}</span>
      </div>

      <div class="app__form" v-if="!loadData && sendedRequest">
        <span class="app__message app__message_primary">{{ this.successMessage }}</span>
        <btn :title="this.language.back" :isGreen="true" :isNoMB="true" @click="goBack"></btn>
      </div>
      </div>

      <btn :title="this.language.call" :isGreen="false" @click="call"></btn>
      <btn :title="this.language.email" :isGreen="false" @click="mailTo"></btn>
      <socialLink></socialLink>
    `,
  },
  ScreenComponentNotFound = {
    template: '<h1>Invalid request</h1>',
  };

const routes = {
  '/sociallink': ScreenComponentMain,
  '/sociallink/calk_delivery': ScreenComponentCalculateDeliveryPrice,
  '/sociallink/search_in_china': ScreenComponentSearchCargoAndManufacture,
};

const SimpleRouter = {
  data: () => ({
    currentRoute: window.location.pathname,
    title: '',
  }),

  beforeCreate() {
    if (!/Android|iPhone|iPad|iPod|Opera Mini/i.test(window.navigator.userAgent)) {
      window.location.href = '/';
    }
  },

  beforeMount() {
    window.addEventListener('popstate', this.historyNavigateHandler);
  },

  methods: {
    goToScreen(src) {
      window.history.pushState({data: this.data, title: this.title}, this.title, src);
      this.currentRoute = src;
    },

    historyNavigateHandler(event) {
      this.goToScreen(window.location.pathname);
    }
  },

  computed: {
    CurrentComponent() {
      return routes[this.currentRoute] || ScreenComponentNotFound
    }
  },


  render() {
    return h(this.CurrentComponent);
  }
}

createApp(SimpleRouter)
  .component('btn', buttonComponent)
  .component('socialLink', socialLinkComponent)
  .component('selector', selectorComponent)
  .component('field', inputComponent)
  .component('textField', textInputComponent)
  .mount('#app');


class Sender {
  constructor(data, callback, path) {
    this.data = data;
    this.path = path;
    this.token = document.head.querySelector('meta[name="csrf-token"]');
    if (!callback) {
      this.callback = function () {
      };
    } else {
      this.callback = callback;
    }
  }

  send() {
    let self = this;
    fetch(this.path, {
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json; charset=utf-8',
        'X-CSRF-TOKEN': $(self.token).prop('content'),
      },
      credentials: 'include',
      method: "POST",
      body: JSON.stringify(this.data)
    }).then(function (res) {
      self.callback(res);
    });
  }

  sendGet() {
    let
      self = this;
    fetch(this.path + '?' + self.data).then(function (res) {
      self.callback(res);
    });
  }
}
