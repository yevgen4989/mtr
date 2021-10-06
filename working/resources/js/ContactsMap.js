import YandexMap from "./YandexMap";
import YandexPlacemark from "./YandexPlacemark";

export default class ContactsMap {
    constructor() {
        this.SELECTOR_MAP = '.contact-map';
        this.$map = $(this.SELECTOR_MAP);
        if (this.$map.length == 0) return;
        this.defaultLongitude = this.$map.data('longitude');
        this.defaultLatitude  = this.$map.data('latitude');
        this.yandexMap = new YandexMap(this.$map[0], this.defaultLongitude, this.defaultLatitude, 18);
        this.yandexMap.addZoomControl().setType(this.yandexMap.TYPE_SCHEMA);
        this.yandexMap.addPlacemark(new YandexPlacemark(this.$map.data('longitude'), this.$map.data('latitude')));
        this.yandexMap.buildMap();
    }
}
