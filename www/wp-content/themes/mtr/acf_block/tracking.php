<?php
/**
 * Acf block: Трекинг груза
 *
 * @var array $block
 */

$data = get_fields($block['id']);
?>


<div class="content-grid">
    <div class="content-grid__item content-grid__item_full">
        <div class="media-object media-object_center">
            <div class="media-object__title media-object__title_margin-big">
                <h2 class="title title__size2 title_no-margin">
                    Трекинг <span class="title__explanation">груза</span>
                </h2>
            </div>
            <div class="media-object__content media-object__content_margin-big">
                <form class="form-inline track_cargo" action="/tracking" method="POST">
                    <div class="form-group form-group_part-1">
                        <input type="text" name="track_number" class="form-control" placeholder="Трек номер груза">
                        <div class="hint">Обязательное для заполнения</div>
                    </div>
                    <div class="form-group form-group_part-1">
                        <a href="#" class="button button_success button_as-input">Проверить</a>
                    </div>
                </form>
            </div>

            <div class="media-object__link text-gray">
                Для получения полной информации по грузу, обратитесь к Вашему менеджеру
            </div>
        </div>
    </div>
</div>

