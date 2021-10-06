export default class YandexPlacemark {
    constructor(longitude, latitude) {
        this.longitude  = longitude;
        this.latitude   = latitude;
        this.placemark  = null;

        this.observers  = [];
    }

    getCoords() {
        return [this.longitude, this.latitude];
    }

    getPlacemark() {
        return this.placemark;
    }

    setObserver(observerAction) {
        this.observers.push(observerAction);
        return this;
    }

    buildPlacemark() {
        if (this.placemark) {

        } else {
            let self = this;
            ymaps.ready(function () {
                self.placemark = new ymaps.Placemark(self.getCoords());

                if (self.observers.length > 0) {
                    for(let i = 0; i < self.observers.length; i++) {
                        self.observers[i](self.getPlacemark());
                    }
                }
            });
        }
    }
}