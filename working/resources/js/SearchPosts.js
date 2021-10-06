export default class SearchPosts {
    constructor () {
        this.SELECTOR_SEARCH_INPUT = '#searchPosts';

        this.$searchInput = $(this.SELECTOR_SEARCH_INPUT);
        if (this.$searchInput.length == 0) {
            return;
        }

        let self = this;
        this.$searchInput.bind('focusout', function (ev) {
            ev.stopPropagation();
            ev.preventDefault();

            if (self.$searchInput.val().length > 0) {
                self.sendSearchQuery(self.$searchInput.val());
            }
        });

        this.$searchInput.bind('keypress', function(ev) {
            if (ev.which == 13) {
                if (self.$searchInput.val().length > 0) {
                    self.sendSearchQuery(self.$searchInput.val());
                    return false;
                }
            }
        });
    }

    sendSearchQuery(searchString) {
        if (window.location.search.length > 0) {
          if(false !== window.location.search.indexOf('search')){
            var params = window.location.search.replace('?', '');
            params = params.split('=');
            params[1] = encodeURI(searchString);
            window.location.href = window.location.origin + window.location.pathname + '?' + params.join('=');
          }else {
            window.location += '&search=' + encodeURI(searchString);
          }
        } else {
            window.location += '?search=' + encodeURI(searchString);
        }
    }
}
