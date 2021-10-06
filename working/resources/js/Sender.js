export default class Sender {
    constructor(data, callback, path) {
        this.data = data;
        this.path = path;
        this.token = document.head.querySelector('meta[name="csrf-token"]');
        if (!callback) {
            this.callback = function () {};
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
        }).then(function(res) {
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