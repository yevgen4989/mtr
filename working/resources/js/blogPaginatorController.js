import Sender from "./Sender";

export default class blogPaginatorController {
    constructor() {
        this.TMP_ITEM = '<a href="" class="announcement__item">' +
                            '<div class="announcement__item__title">' +
                                '<h3 class="title title__size3"></h3>' +
                            '</div>' +
                            '<div class="announcement__item__labels">' +
                                '<span class="label">Статьи</span>' +
                            '</div>' +
                        '</a>';
        this.TMP_TITLE_EXP = '<span class="title__explanation"></span>';
        this.TMP_BACKGROUND_POST_IMG = '<img src="" class="announcement__item__background" alt="">';
        this.TMP_BACKGROUND_POST_DECORATOR = '<div class="announcement__item__gradient"></div>';
        this.CLASS_EMPTY_IMG = 'announcement__item__bordered';
        this.CLASS_BACKGROUNDED_POST_HEADER = 'title_white';
        this.SELECTOR_ELEMENT = '.blog';
        this.SELECTOR_ITEM = '.announcement__item ';
        this.SELECTOR_ACTIVE_BUTTON = '.panel__data .button_active';
        this.SELECTOR_SEARCH_INPUT = '#searchPosts';
        this.$element = $(this.SELECTOR_ELEMENT);
        if (this.$element.length == 0) return;
        this.activeType = $(this.SELECTOR_ACTIVE_BUTTON).attr('data-name');
        this.$searchInput = $(this.SELECTOR_SEARCH_INPUT);
        this.excludeItems = [];
        this.post_type = 'post';
        this.PATH = '/blog/';
        this.per_page = $(this.SELECTOR_ELEMENT).attr('data-per-page');
        this.currentPage = 1;
        this.canUpdate = true;
        let self = this;

        $(document).bind('scroll', function (ev) {

            var pos = self.getVerticalScrollPercentage(document.body)

            if (Math.round(pos) > 50 && self.canUpdate) {
                var excludeItems = [];
                $(self.SELECTOR_ITEM).each(function () {
                  excludeItems.push($(this).attr('data-item'));
                });

                $.post(wp.url,
                  {
                  'post_type': self.post_type,
                  'type': self.activeType,
                  'search': self.$searchInput.val(),
                  'exclude': excludeItems,
                  'per_page': self.per_page,
                  'action': 'get_posts_and_acf',
                  },
                  function (data) {}
                )
                  .then(
                    self.handleAnswer.bind(self)
                  );

                self.currentPage++;
                self.canUpdate = false;
                self.excludeItems = [];
            }
        });
    }

    getVerticalScrollPercentage( elm ){
      var p = elm.parentNode
      return (elm.scrollTop || p.scrollTop) / (p.scrollHeight - p.clientHeight ) * 100
    }

    handleAnswer(data) {
        data = JSON.parse(data);

        if (data.count > 0) {
            for(let i = 0; i < data.count; i++) {
                this.$element.append(this.generateHtmlItem(data.posts[i]));
            }
            this.canUpdate = true;
        } else {
            this.canUpdate = false;
        }
    }

    generateHtmlItem(item) {
      if (!item) return;
      var $tmp = $(this.TMP_ITEM);
      $tmp.attr('href', item.guid);
      $tmp.attr('data-item', item.ID);

      if (!item.post_image) {
        $tmp.addClass(this.CLASS_EMPTY_IMG);
      }
      else {
        var $img = $(this.TMP_BACKGROUND_POST_IMG);
        $img.attr('src', item.post_image[0]);
        $img.attr('width', item.post_image[1]);
        $img.attr('height', item.post_image[2]);
        $img.attr('alt', item.post_title);
        var $decorator = $(this.TMP_BACKGROUND_POST_DECORATOR);
        $tmp.prepend($decorator);
        $tmp.prepend($img);
        $tmp.find('h3').addClass(this.CLASS_BACKGROUNDED_POST_HEADER);
      }

      $tmp.find('h3').text(item.acf.title);

      if (!item.acf.additional_to_title) {
        var $addName = $(this.TMP_TITLE_EXP);
        $addName.text(item.acf.additional_to_title);
        $tmp.find('h3').append($addName);
      }
      $tmp.find('.label').text(item.category[0].name);
      this.$element.append($tmp);
    }


}
