export default class YandexMap {
    constructor(selector, longitude, latitude, mapZoom, callBackFunction) {
        if(!ymaps) {
            throw new Error('ymaps object is not exists');
        }

        this.selector = selector;
        this.map = null;
        this.longitude  = longitude;
        this.latitude   = latitude;
        this.mapZoom    = mapZoom;
        this.callBackFucntion = callBackFunction;
        this.controls   = [];
        this.TYPE_SCHEMA    = 'yandex#map';
        this.TYPE_SATELLITE = 'yandex#satellite';
        this.TYPE_HYBRID    = 'yandex#hybrid';
        this.type = null;

        this.isInputSearch  = false;
        this.isZoomControl  = false;
        this.actions        = new Map();
        this.observers      = new Map();

        this.inputSearch                    = null;
        this.selectSearchResultCallback     = null;
        this.searchRequestSubmitCallback    = null;
        this.searchQueryString              = null;

        this.placemarks = [];

    }

    /**
     * Устанавливает элемент или id элемента где будет развернута карта
     * @param selector
     * @returns {YandexMap}
     */
    setSelector(selector) {
        this.selector = selector;
        return this;
    }

    /**
     * Добавляет метку на карту
     * @param placemark
     * @returns {YandexMap}
     */
    addPlacemark(placemark) {
        if (typeof placemark === 'object' && placemark.constructor.name === 'YandexPlacemark') {
            var self = this;
            placemark.setObserver(function (e) {
                self.addPlacemark(e);
            }).buildPlacemark();
            return null;
        }

        this.placemarks.push(placemark);
        if (this.map) {
            this.map.geoObjects.add(placemark);
        }
        return this;
    }

    setSearchQueryString(query) {
        this.searchQueryString = query;
        if (this.inputSearch) {
            this.inputSearch.state.set('request',           this.searchQueryString);
            this.inputSearch.state.set('originalRequest',   this.searchQueryString);
            this.inputSearch.state.set('inputValue',        this.searchQueryString);
        }
    }

    /**
     * Устанавливает обработчки на выбор места на карте
     * колбек функция получает массив координат в формате [longitude, latitude]
     * @param action
     * @returns {YandexMap}
     */
    setSelectSearchResultCallback(action) {
        this.selectSearchResultCallback = action;
        return this;
    }

    /**
     * Устанавлиает обработчик на отправку результата поискового запроса
     * в солбек функцию передается объект карты
     * @param action
     * @returns {YandexMap}
     */
    setSearchRequestSubmitCallback(action) {
        this.searchRequestSubmitCallback = action;
        return this;
    }

    /**
     * Устанавливает действие на карту
     * @param nameAction название события
     * @param action фунция реализующая обработку действия
     * @returns {YandexMap}
     */
    setAction(nameAction, action) {
        this.actions.set(nameAction, action);
        return this;
    }

    /**
     * Удаляет обработчик события
     * @param nameAction
     * @returns {YandexMap}
     */
    removeAction(nameAction) {
        this.actions.delete(nameAction);
        return this;
    }

    /**
     * Устаналвиает функции наблюдатели за загрузкой карты
     * принимающая функция получает непосредственно объект карты
     * @param nameObserver
     * @param action
     * @returns {YandexMap}
     */
    setObserver(nameObserver, action) {
        this.observers.set(nameObserver, action);
        return this;
    }

    /**
     * Удаляет налюдателя
     * @param nameObserver
     * @returns {YandexMap}
     */
    removeObserver(nameObserver) {
        this.observers.delete(nameObserver);
        return this;
    }

    /**
     * Устанавливает широту центра
     * @param latitude
     * @returns {YandexMap}
     */
    setLatitude(latitude) {
        this.latitude = latitude;
        //если проинициализирована карта, то сразу применим изменения
        if(this.map) {
            this.map.setCenter([this.longitude, this.latitude], this.mapZoom);
        }
        return this;
    }

    /**
     * Устанавливает долготу центра
     * @param longitude
     * @returns {YandexMap}
     */
    setLongitude(longitude) {
        this.longitude = longitude;
        if(this.map) {
            this.map.setCenter([this.longitude, this.latitude], this.mapZoom);
        }
        return this;
    }

    /**
     * Устанавливает центр карты
     * @param longitude
     * @param latitude
     * @param zoom
     */
    setCenter(longitude, latitude, zoom) {
        this.longitude = longitude;
        this.latitude = latitude;
        this.mapZoom = zoom;
        if (this.map) {
            this.map.setCenter([this.longitude, this.latitude], this.mapZoom);
        }
    }

    /**
     * Устанавливает зум карты
     * @param zoom
     * @returns {YandexMap}
     */
    setZoom(zoom) {
        this.mapZoom = zoom;
        if(this.map) {
            this.map.setZoom(this.mapZoom, {
                duration: 1000,
            })
        }
        return this;
    }

    /**
     * Устанавливает тип карты
     * @param type
     * @returns {YandexMap}
     */
    setType(type) {
        switch (type) {
            case this.TYPE_HYBRID:
                this.type = this.TYPE_HYBRID;
                break;
            case this.TYPE_SATELLITE:
                this.type = this.TYPE_SATELLITE;
                break;
            default:
                this.type = this.TYPE_SCHEMA;
        }

        if(this.map) {
            this.map.setType(this.type);
        }

        return this;
    }

    /**
     * Выставляет флаг необходимости инициализации формы поиска на карте
     * @returns {YandexMap}
     */
    addInputSearch() {
        this.isInputSearch = true;
        return this;
    }

    /**
     * Выставляет флаг необходимости инициализации бегунка зума карты
     * @returns {YandexMap}
     */
    addZoomControl() {
        this.isZoomControl = true;
        return this;
    }

    /**
     * Удаляет все плейсмарки
     * @returns {YandexMap}
     */
    removePlacemarks() {
        this.placemarks = [];
        if (this.map) {
            this.map.geoObjects.removeAll();
        }

        return this;
    }

    closeBalloon() {
        if (this.map) {
            this.map.balloon.close();
        }

        return this;
    }

    /**
     * Инициализирует построение карты по заданным параметрам
     */
    buildMap() {
        this.destroyMap();

        var self = this;
        ymaps.ready(function () {
            //установим все элементы управления на карту
            if (self.isInputSearch) {
                self.inputSearch = new ymaps.control.SearchControl({
                    options: {
                        size: 'large',
                        provider: 'yandex#map'
                    }
                });

                if (!self.searchQueryString) {
                    self.setSearchQueryString(self.searchQueryString);
                }

                self.inputSearch.parentFasade = self;

                if (typeof self.selectSearchResultCallback === 'function') {
                    self.inputSearch.events.add('resultselect', function (e) {
                        let results     = self.inputSearch.getResultsArray();
                        let selected    = e.get('index');
                        let point       = results[selected].geometry.getCoordinates();
                        let parent      = e.get('target').parentFasade;
                        let meta        = parent.inputSearch.getResponseMetaData();
                        parent.searchQueryString = meta.request;
                        parent.selectSearchResultCallback(point, parent.searchQueryString);
                    });
                }

                if (typeof self.searchRequestSubmitCallback === 'function') {
                    self.inputSearch.events.add('submit', function (e) {
                        let parent = e.get('target').parentFasade;
                        parent.searchRequestSubmitCallback(parent);
                    });
                }

                if (self.searchQueryString != '') {
                    self.setSearchQueryString(self.searchQueryString);
                }

                self.controls.push(self.inputSearch);
            }

            if (self.isZoomControl) {
                self.controls.push('zoomControl');
            }



            self.map = new ymaps.Map(self.selector, {
                zoom:       self.mapZoom,
                center:     [self.longitude, self.latitude],
                controls:   self.controls,
                type:       self.type,
            });

            //установим все плейсмарки если они есть
            if (self.placemarks.length > 0) {
                for (let i = 0; i < self.placemarks.length; i++) {
                    self.map.geoObjects.add(self.placemarks[i]);
                }
            }

            //установим действия для карты
            if (self.actions.size > 0) {
                for (let action of self.actions.entries()) {
                    self.map.events.add(action[0], action[1]);
                }
            }

            //Вызовем колбек функцию, если она установлена
            if (typeof self.callBackFucntion === 'function') {
                self.callBackFucntion(self.getMap());
            }

            //Вызовем все методы наблюдателей и передадим им саму карту
            if (self.observers.size > 0) {
                for (let observer of self.observers.entries()) {
                    if (typeof observer[1] === 'function') {
                        observer[1](self.getMap());
                    }
                }
            }
        });
    }

    /**
     * Удаляет карту
     */
    destroyMap() {
        if (this.map) {
            this.map.destroy();
            this.controls = [];
        }
        return this;
    }

    /**
     * Возвращает непосредственно сам объект карты
     * @returns {null}
     */
    getMap() {
        return this.map;
    }
}