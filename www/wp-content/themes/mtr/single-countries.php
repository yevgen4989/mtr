<?php get_header();?>

<?

if($post->post_parent == 0){
    $storeFrom = get_field('store', get_the_ID());
    $storeTo = get_field('store', get_option('main_country'));
    $deliveryMethod = new WP_Query([
        'post_type'     => 'delivery_methods',
        'post_status'   => 'publish',
        'posts_per_page'=> 1,
    ]);
    $deliveryMethod = $deliveryMethod->posts[0];

    $deliveryRoute = new WP_Query( [
        'post_type'     => 'delivery_route',
        'post_status'   => 'publish',
        'posts_per_page'=> -1,
        'meta_query' => [
            'relation' => 'AND',
            [
                'key' => 'from_store',
                'value' => $storeFrom->ID
            ],
            [
                'key' => 'to_store',
                'value' => $storeTo->ID
            ],
            [
                'key' => 'delivery_method',
                'value' => $deliveryMethod->ID
            ],
        ]
    ]);
    $deliveryRoute = $deliveryRoute->posts[0];
    $costPerKilo = get_field('cost_per_kilo', $deliveryRoute->ID);


    $type_cargos = new WP_Query( [
        'post_type'     => 'type_cargos',
        'post_status'   => 'publish',
        'posts_per_page'=> -1,
        'meta_query' => [ [
            'key' => 'popular',
            'value' => 1
        ]
        ]
    ]);
    $type_cargos = $type_cargos->posts;
    $arAllPopularType = array();
    foreach ($type_cargos as $cargo){
        $arAllPopularType[] = array(
            'id'   => $cargo->ID,
            'name' => $cargo->post_title
        );
    }
    usort($arAllPopularType, function ($item1, $item2) {
        return $item1['name'] <=> $item2['name'];
    });
?>
    <script>
        var referer = "<?= isset($_SERVER['HTTP_REFERER']) ?? ''?>";
    </script>

    <div class="content">
        <div class="content-grid">
            <div class="content-grid__item content-grid__item_twice content-grid__item_no-background content-grid__item_clear">
                <div class="announcement announcement_single" data-route-price="<?=$costPerKilo?>">
                    <div class="announcement__item">
                        <img src="<?=get_the_post_thumbnail_url(get_the_ID(), array(903, 220))?>" class="announcement__item__background" alt="доставка из <?=get_city_ru_dative(get_the_title())?>">
                        <div class="announcement__item__gradient announcement__item__gradient_black"></div>
                        <h1 class="announcement__item__title announcement__item__title_text">
                            Из <?=get_city_ru_dative(get_the_title())?> в <?=get_city_ru_accusative(get_the_title(get_option('main_country')))?>
                            <span class="announcement__item__title_text_explanation">
                                <?
                                if( has_excerpt( get_the_ID() ) ){
	                                the_excerpt();
                                }else{
	                                echo 'Рассчитать стоимость международной доставки и оформления груза';
                                }
                                ?>
                            </span>
                        </h1>
                        <div class="announcement__item__labels">
                            <a class="label label_white" href="<?=get_the_permalink(get_option('page_delivery'))?>">Из других стран</a>
                        </div>
                    </div>
                    <input type="hidden" name="country_id" value="<?=get_the_ID()?>">
                    <input type="hidden" name="delivery_routes_id" value="<?=$deliveryRoute->ID?>">
                </div>

                <div class="panel panel__as-announcement-item">
                    <div class="panel__data panel__data_full panel__data_radial-white">
                        <div class="form-group">
                            <div class="form-group__label">
                                <a href="#cargoTypeInfo" class="info">i</a>
                                Содержимое груза:
                            </div>
                            <div class="form-group form-group_short">
                                <input class="form-control form-control_margin form-control_select form-control__mobile-big-padding" name="cargo" placeholder="Выберите или введите">
                                <input type="hidden" name="type_cargo_id" value="">
                                <div class="hint-window">
                                    <div class="hint-widow__title">Невозможно рассчитать стоимость для данного груза в автоматическом режиме</div>
                                    <div class="hint-widow__body">
                                        <div class="hint-widow__body__group">
                                            <a href="#" class="button button_white button_bordered">
                                                + Добавить для рассчета стоимости
                                            </a>
                                        </div>
                                        <div class="hint-widow__body__group">
                                            Можете поискать по другим ключевым словам и выбрать из списка или отправить заявку для рассчета стоимости.
                                        </div>
                                        <div class="hint-widow__body__group">
                                            Точную информацию о возможности доставки вашего типа груза уточнят наши менеджеры после отправки заявки.
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group__label form-group__label_small">
                                Популярные:
                            </div>
                            <?foreach ($arAllPopularType as $pop){?>
                                <a href="#" class="label label_white label_bordered label_margined type_item" data-id="<?=$pop['id']?>"><?=$pop['name']?></a>
                            <?}?>
                        </div>
                    </div>
                </div>

                <div class="panel panel__as-announcement-item" id="weight">
                    <div class="panel__data panel__data_full panel__data_radial-white">
                        <div class="form-group">
                            <div class="form-group__label">
                                <a href="#weightCargo" class="info">i</a>
                                Вес груза:
                            </div>
                            <div class="slider cargo_weight">
                                <div class="slider__content-place slider__content-place__like-input">
                                    <div class="slider__input-place">0</div>
                                    <div class="slider__explanation">
                                        <sub>кг</sub>
                                    </div>
                                </div>
                                <div class="slider__content-place">
                                    <div class="slider__toggle">
                                        <div class="slider__toggle__background"><div class="slider__toggle__background__selected"></div></div>
                                        <span class="slider__toggle__pin"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group__label form-group__label_small">
                                Популярные:
                            </div>
                            <a href="#" class="label label_white label_bordered label_margined weight_item" data-weight="10">10 кг</a>
                            <a href="#" class="label label_white label_bordered label_margined weight_item" data-weight="20">20 кг</a>
                            <a href="#" class="label label_white label_bordered label_margined weight_item" data-weight="30">30 кг</a>
                            <a href="#" class="label label_white label_bordered label_margined weight_item" data-weight="50">50 кг</a>
                            <a href="#" class="label label_white label_bordered label_margined weight_item" data-weight="100">100 кг</a>
                            <a href="#" class="label label_white label_bordered label_margined weight_item" data-weight="300">300 кг</a>
                            <a href="#" class="label label_white label_bordered label_margined weight_item" data-weight="1000">1000 кг</a>
                        </div>
                    </div>
                </div>

                <div class="panel panel__as-announcement-item" id="volume">
                    <div class="panel__data panel__data_full panel__data_radial-white">
                        <div class="form-group">
                            <div class="form-group__label">
                                <a href="#volumeCargo" class="info">i</a>
                                Объем груза:
                            </div>
                            <div class="slider cargo_volume">
                                <div class="slider__content-place slider__content-place__like-input">
                                    <div class="slider__input-place">0</div>
                                    <div class="slider__explanation">
                                        <sub>м<sup>3</sup></sub>
                                    </div>
                                </div>
                                <div class="slider__content-place">
                                    <div class="slider__toggle">
                                        <div class="slider__toggle__background"><div class="slider__toggle__background__selected"></div></div>
                                        <span class="slider__toggle__pin"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group__label form-group__label_small">
                                Популярные:
                            </div>
                            <a href="#" class="label label_white label_bordered label_margined volume_item" data-volume="1">1 м<sup>3</sup></a>
                            <a href="#" class="label label_white label_bordered label_margined volume_item" data-volume="5">5 м<sup>3</sup></a>
                            <a href="#" class="label label_white label_bordered label_margined volume_item" data-volume="10">10 м<sup>3</sup></a>
                            <a href="#" class="label label_white label_bordered label_margined volume_item" data-volume="20">20 м<sup>3</sup></a>
                            <a href="#" class="label label_white label_bordered label_margined volume_item" data-volume="50">50 м<sup>3</sup></a>
                            <a href="#" class="label label_white label_bordered label_margined volume_item" data-volume="100">100 м<sup>3</sup></a>

                        </div>
                    </div>
                </div>

                <div class="panel panel__as-announcement-item">
                    <div class="panel__data panel__data_full panel__data_radial-white">
                        <div class="form-group">
                            <div class="form-group__label">
                                <a href="#locationCargo" class="info">i</a>
                                Информация о доставке
                            </div>
                            <div class="form-group form-group_short">
                                <input class="form-control form-control_margin" name="city_from" placeholder="Город отправления">
                            </div>
                            <div class="form-group form-group_short form-group__mobile-margined">
                                <input class="form-control form-control_margin" name="city_to" placeholder="Город назначения">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="panel panel__as-announcement-item">
                    <div class="panel__data panel__data_full panel__data_radial-white">
                        <div class="form-group">
                            <div class="form-group__label">
                                <a href="#contactData" class="info">i</a>
                                Контактные данные:
                            </div>
                            <div class="form-group form-group_short">
                                <input class="form-control form-control_margin" name="name" placeholder="Как вас зовут">
                            </div>
                            <div class="form-group form-group_short">
                                <input class="form-control form-control_margin" name="phone" placeholder="Номер телефона">
                            </div>
                            <div class="form-group form-group_short form-group__mobile-margined">
                                <input class="form-control form-control_margin" name="email" placeholder="Электронная почта">
                            </div>
                            <div class="form-group form-group_short hide-mobile">
                                <a href="#" class="button button_success button_big-inline send">Оставить заявку</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="panel panel__as-announcement-item hide-desktop">
                    <div class="panel__data panel__data_full panel__data_radial-white">
                        <div class="action-form">
                            <div class="total-block">
                                <div class="total-block__price">
                                    <span class="price">0</span> <span class="total-block__price__currency">&#36;</span>
                                </div>
                                <div class="total-block__action">
                                    <a href="#" class="button button_big button_success send">Оставить заявку</a>
                                </div>
                                <div class="total-block__info">
                                    <p>Нажимая кнопку «Оставить заявку», вы соглашаетесь с <a href="<?=get_privacy_policy_url()?>" target="_blank">Политикой конфиденциальности</a></p>
                                    <p>Доставка до склада и до клиента рассчитывается отдельно и зависит <a href="#">от различных условий</a></p>
                                    <p>Расчет доставки производится от нашего склада в ЕС до нашего склада в Москве</p>
                                    <p>Для получения полной информации обратитесь к менеджеру по телефону или отправьте заявку</p>
                                    <p>Не является офертой</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <?if(get_the_content() != null){?>
                    <div class="panel panel_nmargin">
                        <div class="panel__data panel__data_full panel__data_radial-white">
                            <div class="edited-text">
                                <?the_content()?>
                            </div>
                        </div>
                    </div>
                <?}?>
            </div>
            <div class="content-grid__item content-grid__item__once content-grid__item_no-background content-grid__item_clear content-grid__item_nmargin hide-mobile">
                <div class="action-form country-pc">
                    <div class="total-block">
                        <div class="total-block__price">
                            <span class="price">0</span> <span class="total-block__price__currency">&#36;</span>
                        </div>
                        <div class="total-block__action">
                            <a href="#" class="button button_big button_success send">Оставить заявку</a>
                        </div>
                        <div class="total-block__info">
                            <p>Нажимая кнопку «Оставить заявку», вы соглашаетесь с <a href="<?=get_privacy_policy_url()?>" target="_blank">Политикой конфиденциальности</a></p>
                            <p>Доставка до склада и до клиента рассчитывается отдельно и зависит <a href="#">от различных условий</a></p>
                            <p>Расчет доставки производится от нашего склада в ЕС до нашего склада в Москве</p>
                            <p>Для получения полной информации обратитесь к менеджеру по телефону или отправьте заявку</p>
                            <p>Не является офертой</p>
                        </div>
                    </div>
                </div>

                <?
                $children_pages = get_posts( array(
                    'numberposts' => -1,
                    'orderby'     => 'id',
                    'order'       => 'ASC',
                    'post_type'   => 'countries',
                    'post_parent' => get_the_ID(),
                ) );

                if(!empty($children_pages)){?>
                    <div class="block__links">
                        <ul class="list list_vertical">
                            <?foreach ($children_pages as $post){?>
                                <li class="list__item list__item_dark">
                                    <a href="<?=get_permalink($post->ID)?>"><?=$post->post_title?></a>
                                </li>
                            <?}?>
                        </ul>
                    </div>
                <?}?>
            </div>
        </div>
    </div>

    <div class="window" id="cargoTypeInfo">
        <div class="window__body window__body_info">
            <a href="#" class="window__close"></a>
            <div class="window__body__title">Содержимое груза</div>
            <div class="window__body__content">
                <p>Определяет какой тип груза необходимо доставить.
                    От этого параметра зависит, как и в каких условиях будет транспортироваться ваш груз.
                    Также это поможет нам при оформлении всех документов для доставки вашего груза.<br><br>
                    Если необходимый груз отсутствует в списке, вы можете ввести наименование своего груза
                    в данное поле самостоятельно
                </p>
            </div>
        </div>
    </div>
    <div class="window" id="weightCargo">
        <div class="window__body window__body_info">
            <a href="#" class="window__close"></a>
            <div class="window__body__title">Вес груза</div>
            <div class="window__body__content">
                <p>Вес груза необходимо знать, чтобы понимать, каким транспортом везти ваш груз, какое оборудование понадобится для погрузки, а также учитывать другие особенности доставки, из которых складывается итоговая стоимость</p>
            </div>
        </div>
    </div>
    <div class="window" id="weightTooLight">
        <div class="window__body window__body_info">
            <a href="#" class="window__close"></a>
            <div class="window__body__title">Слишком маленький вес</div>
            <div class="window__body__content">
                <p>Минимальный вес груза от 10кг.</p>
            </div>
        </div>
    </div>
    <div class="window" id="volumeCargo">
        <div class="window__body window__body_info">
            <a href="#" class="window__close"></a>
            <div class="window__body__title">Объем груза</div>
            <div class="window__body__content">
                <p>Объем груза необходимо знать, чтобы понимать, сколько транспорта понадобится для перевозки вашего груза, сколько места он займет на складе, какое оборудование понадобится для погрузки, а также учитывать другие особенности доставки, из которых складывается итоговая стоимость</p>
            </div>
        </div>
    </div>
    <div class="window" id="locationCargo">
        <div class="window__body window__body_info">
            <a href="#" class="window__close"></a>
            <div class="window__body__title">Информация о доставке</div>
            <div class="window__body__content">
                <p>Информация о начальном и конечном пункте доставки груза позволит нашим менеджерам посчитать точную сумму доставки и предоставить вам подходящее по срокам и стоимости предложение.</p>
            </div>
        </div>
    </div>
    <div class="window" id="contactData">
        <div class="window__body window__body_info">
            <a href="#" class="window__close"></a>
            <div class="window__body__title">Контактные данные</div>
            <div class="window__body__content">
                <p>Нам нужны ваши контактные данные, чтобы связаться по поводу вашей заявки, уточнить детали и предложить подходящие условия международной доставки.</p>
            </div>
        </div>
    </div>
    <div class="window" id="emptyCargoType">
        <div class="window__body window__body_info">
            <a href="#" class="window__close"></a>
            <div class="window__body__title">Ошибка рассчета</div>
            <div class="window__body__content">
                <p>Необходимо указать тип груза для дальнейшего рассчета стоимости доставки</p>
            </div>
        </div>
    </div>
    <div class="window" id="errors">
        <div class="window__body window__body_info">
            <a href="#" class="window__close"></a>
            <div class="window__body__title">Ошибка отправки формы</div>
            <div class="window__body__content">
            </div>
        </div>
    </div>
    <div class="window" id="success">
        <div class="window__body window__body_info">
            <a href="#" class="window__close"></a>
            <div class="window__body__title">Расчет успешно отправлен</div>
            <div class="window__body__content">
                Ваш расчет успешно отправлен. В ближайшее время с Вами свяжутся наши менеджеры для уточнения деталей доставки.
            </div>
        </div>
    </div>
    <div class="window" id="moreThanMax">
        <div class="window__body window__body_info">
            <a href="#" class="window__close"></a>
            <div class="window__body__title">Нужно указать больше?</div>
            <div class="window__body__content">
                Чтобы вручную указать точный вес от 2000 кг до 20000кг, кликните по значению 2000 кг
            </div>
        </div>
    </div>
    <div class="window" id="cargoTypeIsEmty">
        <div class="window__body window__body_info">
            <a href="#" class="window__close"></a>
            <div class="window__body__title">Не указано содержимое груза</div>
            <div class="window__body__content">
                Чтобы расчитать стоимость доставки, необходимо указать содержимое груза. <br><br>
                В случае, если указанное содержимое груза не найдено, можете указать его вручную, а также заполнить остальные поля, в таком случае
                заявка будет отправлена на составление расчета по вашему грузу.
            </div>
        </div>
    </div>

<?}else{
    if(get_post($post->post_parent)->post_parent == 0){

        $storeFrom = get_field('store', $post->post_parent);
        $storeTo = get_field('store', get_option('main_country'));
        $deliveryMethod = new WP_Query([
            'post_type'     => 'delivery_methods',
            'post_status'   => 'publish',
            'posts_per_page'=> 1,
        ]);
        $deliveryMethod = $deliveryMethod->posts[0];

        $deliveryRoute = new WP_Query( [
            'post_type'     => 'delivery_route',
            'post_status'   => 'publish',
            'posts_per_page'=> -1,
            'meta_query' => [
                'relation' => 'AND',
                [
                    'key' => 'from_store',
                    'value' => $storeFrom->ID
                ],
                [
                    'key' => 'to_store',
                    'value' => $storeTo->ID
                ],
                [
                    'key' => 'delivery_method',
                    'value' => $deliveryMethod->ID
                ],
            ]
        ]);
        $deliveryRoute = $deliveryRoute->posts[0];
        $costPerKilo = get_field('cost_per_kilo', $deliveryRoute->ID);


        $type_cargos = new WP_Query( [
            'post_type'     => 'type_cargos',
            'post_status'   => 'publish',
            'posts_per_page'=> -1,
            'meta_query' => [ [
                'key' => 'popular',
                'value' => 1
            ]
            ]
        ]);
        $type_cargos = $type_cargos->posts;
        $arAllPopularType = array();
        foreach ($type_cargos as $cargo){
            $arAllPopularType[] = array(
                'id'   => $cargo->ID,
                'name' => $cargo->post_title
            );
        }
        usort($arAllPopularType, function ($item1, $item2) {
            return $item1['name'] <=> $item2['name'];
        });
        ?>
        <script>
            var referer = "<?=$_SERVER['HTTP_REFERER']?>";
        </script>

        <div class="content">
            <div class="content-grid">
                <div class="content-grid__item content-grid__item_twice content-grid__item_no-background content-grid__item_clear">
                    <div class="announcement announcement_single" data-route-price="<?=$costPerKilo?>">
                        <div class="announcement__item">
                            <img src="<?=get_the_post_thumbnail_url(get_the_ID(), array(903, 220))?>" class="announcement__item__background" alt="доставка из <?=get_city_ru_dative(get_the_title())?>">
                            <div class="announcement__item__gradient announcement__item__gradient_black"></div>
                            <h1 class="announcement__item__title announcement__item__title_text">
                                <?the_title()?>
                                <span class="announcement__item__title_text_explanation">
                                <?
                                if( has_excerpt( get_the_ID() ) ){
	                                the_excerpt();
                                }else{
	                                echo 'Рассчитать стоимость международной доставки и оформления груза';
                                }
                                ?>
                            </span>
                            </h1>
                            <div class="announcement__item__labels">
                                <a class="label label_white" href="<?=get_the_permalink(get_option('page_delivery'))?>">Из других стран</a>
                            </div>
                        </div>
                        <input type="hidden" name="country_id" value="<?=$post->post_parent?>">
                        <input type="hidden" name="delivery_routes_id" value="<?=$deliveryRoute->ID?>">
                    </div>

                    <div class="panel panel__as-announcement-item">
                        <div class="panel__data panel__data_full panel__data_radial-white">
                            <div class="form-group">
                                <div class="form-group__label">
                                    <a href="#cargoTypeInfo" class="info">i</a>
                                    Содержимое груза:
                                </div>
                                <div class="form-group form-group_short">
                                    <input class="form-control form-control_margin form-control_select form-control__mobile-big-padding" name="cargo" placeholder="Выберите или введите">
                                    <input type="hidden" name="type_cargo_id" value="">
                                    <div class="hint-window">
                                        <div class="hint-widow__title">Невозможно рассчитать стоимость для данного груза в автоматическом режиме</div>
                                        <div class="hint-widow__body">
                                            <div class="hint-widow__body__group">
                                                <a href="#" class="button button_white button_bordered">
                                                    + Добавить для рассчета стоимости
                                                </a>
                                            </div>
                                            <div class="hint-widow__body__group">
                                                Можете поискать по другим ключевым словам и выбрать из списка или отправить заявку для рассчета стоимости.
                                            </div>
                                            <div class="hint-widow__body__group">
                                                Точную информацию о возможности доставки вашего типа груза уточнят наши менеджеры после отправки заявки.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group__label form-group__label_small">
                                    Популярные:
                                </div>
                                <?foreach ($arAllPopularType as $pop){?>
                                    <a href="#" class="label label_white label_bordered label_margined type_item" data-id="<?=$pop['id']?>"><?=$pop['name']?></a>
                                <?}?>
                            </div>
                        </div>
                    </div>

                    <div class="panel panel__as-announcement-item" id="weight">
                        <div class="panel__data panel__data_full panel__data_radial-white">
                            <div class="form-group">
                                <div class="form-group__label">
                                    <a href="#weightCargo" class="info">i</a>
                                    Вес груза:
                                </div>
                                <div class="slider cargo_weight">
                                    <div class="slider__content-place slider__content-place__like-input">
                                        <div class="slider__input-place">0</div>
                                        <div class="slider__explanation">
                                            <sub>кг</sub>
                                        </div>
                                    </div>
                                    <div class="slider__content-place">
                                        <div class="slider__toggle">
                                            <div class="slider__toggle__background"><div class="slider__toggle__background__selected"></div></div>
                                            <span class="slider__toggle__pin"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group__label form-group__label_small">
                                    Популярные:
                                </div>
                                <a href="#" class="label label_white label_bordered label_margined weight_item" data-weight="10">10 кг</a>
                                <a href="#" class="label label_white label_bordered label_margined weight_item" data-weight="20">20 кг</a>
                                <a href="#" class="label label_white label_bordered label_margined weight_item" data-weight="30">30 кг</a>
                                <a href="#" class="label label_white label_bordered label_margined weight_item" data-weight="50">50 кг</a>
                                <a href="#" class="label label_white label_bordered label_margined weight_item" data-weight="100">100 кг</a>
                                <a href="#" class="label label_white label_bordered label_margined weight_item" data-weight="300">300 кг</a>
                                <a href="#" class="label label_white label_bordered label_margined weight_item" data-weight="1000">1000 кг</a>
                            </div>
                        </div>
                    </div>

                    <div class="panel panel__as-announcement-item" id="volume">
                        <div class="panel__data panel__data_full panel__data_radial-white">
                            <div class="form-group">
                                <div class="form-group__label">
                                    <a href="#volumeCargo" class="info">i</a>
                                    Объем груза:
                                </div>
                                <div class="slider cargo_volume">
                                    <div class="slider__content-place slider__content-place__like-input">
                                        <div class="slider__input-place">0</div>
                                        <div class="slider__explanation">
                                            <sub>м<sup>3</sup></sub>
                                        </div>
                                    </div>
                                    <div class="slider__content-place">
                                        <div class="slider__toggle">
                                            <div class="slider__toggle__background"><div class="slider__toggle__background__selected"></div></div>
                                            <span class="slider__toggle__pin"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group__label form-group__label_small">
                                    Популярные:
                                </div>
                                <a href="#" class="label label_white label_bordered label_margined volume_item" data-volume="1">1 м<sup>3</sup></a>
                                <a href="#" class="label label_white label_bordered label_margined volume_item" data-volume="5">5 м<sup>3</sup></a>
                                <a href="#" class="label label_white label_bordered label_margined volume_item" data-volume="10">10 м<sup>3</sup></a>
                                <a href="#" class="label label_white label_bordered label_margined volume_item" data-volume="20">20 м<sup>3</sup></a>
                                <a href="#" class="label label_white label_bordered label_margined volume_item" data-volume="50">50 м<sup>3</sup></a>
                                <a href="#" class="label label_white label_bordered label_margined volume_item" data-volume="100">100 м<sup>3</sup></a>

                            </div>
                        </div>
                    </div>

                    <div class="panel panel__as-announcement-item">
                        <div class="panel__data panel__data_full panel__data_radial-white">
                            <div class="form-group">
                                <div class="form-group__label">
                                    <a href="#locationCargo" class="info">i</a>
                                    Информация о доставке
                                </div>
                                <div class="form-group form-group_short">
                                    <input class="form-control form-control_margin" name="city_from" placeholder="Город отправления">
                                </div>
                                <div class="form-group form-group_short form-group__mobile-margined">
                                    <input class="form-control form-control_margin" name="city_to" placeholder="Город назначения">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel panel__as-announcement-item">
                        <div class="panel__data panel__data_full panel__data_radial-white">
                            <div class="form-group">
                                <div class="form-group__label">
                                    <a href="#contactData" class="info">i</a>
                                    Контактные данные:
                                </div>
                                <div class="form-group form-group_short">
                                    <input class="form-control form-control_margin" name="name" placeholder="Как вас зовут">
                                </div>
                                <div class="form-group form-group_short">
                                    <input class="form-control form-control_margin" name="phone" placeholder="Номер телефона">
                                </div>
                                <div class="form-group form-group_short form-group__mobile-margined">
                                    <input class="form-control form-control_margin" name="email" placeholder="Электронная почта">
                                </div>
                                <div class="form-group form-group_short hide-mobile">
                                    <a href="#" class="button button_success button_big-inline send">Оставить заявку</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel panel__as-announcement-item hide-desktop">
                        <div class="panel__data panel__data_full panel__data_radial-white">
                            <div class="action-form">
                                <div class="total-block">
                                    <div class="total-block__price">
                                        <span class="price">0</span> <span class="total-block__price__currency">&#36;</span>
                                    </div>
                                    <div class="total-block__action">
                                        <a href="#" class="button button_big button_success send">Оставить заявку</a>
                                    </div>
                                    <div class="total-block__info">
                                        <p>Нажимая кнопку «Оставить заявку», вы соглашаетесь с <a href="<?=get_privacy_policy_url()?>" target="_blank">Политикой конфиденциальности</a></p>
                                        <p>Доставка до склада и до клиента рассчитывается отдельно и зависит <a href="#">от различных условий</a></p>
                                        <p>Расчет доставки производится от нашего склада в ЕС до нашего склада в Москве</p>
                                        <p>Для получения полной информации обратитесь к менеджеру по телефону или отправьте заявку</p>
                                        <p>Не является офертой</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?if(get_the_content() != null){?>
                        <div class="panel panel_nmargin">
                            <div class="panel__data panel__data_full panel__data_radial-white">
                                <div class="edited-text">
                                    <?the_content()?>
                                </div>
                            </div>
                        </div>
                    <?}?>
                </div>
                <div class="content-grid__item content-grid__item__once content-grid__item_no-background content-grid__item_clear content-grid__item_nmargin hide-mobile">
                    <div class="action-form">
                        <div class="total-block">
                            <div class="total-block__price">
                                <span class="price">0</span> <span class="total-block__price__currency">&#36;</span>
                            </div>
                            <div class="total-block__action">
                                <a href="#" class="button button_big button_success send">Оставить заявку</a>
                            </div>
                            <div class="total-block__info">
                                <p>Нажимая кнопку «Оставить заявку», вы соглашаетесь с <a href="<?=get_privacy_policy_url()?>" target="_blank">Политикой конфиденциальности</a></p>
                                <p>Доставка до склада и до клиента рассчитывается отдельно и зависит <a href="#">от различных условий</a></p>
                                <p>Расчет доставки производится от нашего склада в ЕС до нашего склада в Москве</p>
                                <p>Для получения полной информации обратитесь к менеджеру по телефону или отправьте заявку</p>
                                <p>Не является офертой</p>
                            </div>
                        </div>
                    </div>

                    <?
                    $children_pages = get_posts( array(
                        'numberposts' => -1,
                        'orderby'     => 'id',
                        'order'       => 'ASC',
                        'post_type'   => 'countries',
                        'post_parent' => $post->post_parent,
                        'exclude'     => $post->ID
                    ) );

                    if(!empty($children_pages)){?>
                        <div class="block__links">
                            <ul class="list list_vertical">
                                <?foreach ($children_pages as $post){?>
                                    <li class="list__item list__item_dark">
                                        <a href="<?=get_permalink($post->ID)?>"><?=$post->post_title?></a>
                                    </li>
                                <?}?>
                            </ul>
                        </div>
                    <?}?>
                </div>
            </div>
        </div>

        <div class="window" id="cargoTypeInfo">
            <div class="window__body window__body_info">
                <a href="#" class="window__close"></a>
                <div class="window__body__title">Содержимое груза</div>
                <div class="window__body__content">
                    <p>Определяет какой тип груза необходимо доставить.
                        От этого параметра зависит, как и в каких условиях будет транспортироваться ваш груз.
                        Также это поможет нам при оформлении всех документов для доставки вашего груза.<br><br>
                        Если необходимый груз отсутствует в списке, вы можете ввести наименование своего груза
                        в данное поле самостоятельно
                    </p>
                </div>
            </div>
        </div>
        <div class="window" id="weightCargo">
            <div class="window__body window__body_info">
                <a href="#" class="window__close"></a>
                <div class="window__body__title">Вес груза</div>
                <div class="window__body__content">
                    <p>Вес груза необходимо знать, чтобы понимать, каким транспортом везти ваш груз, какое оборудование понадобится для погрузки, а также учитывать другие особенности доставки, из которых складывается итоговая стоимость</p>
                </div>
            </div>
        </div>
        <div class="window" id="weightTooLight">
            <div class="window__body window__body_info">
                <a href="#" class="window__close"></a>
                <div class="window__body__title">Слишком маленький вес</div>
                <div class="window__body__content">
                    <p>Минимальный вес груза от 10кг.</p>
                </div>
            </div>
        </div>
        <div class="window" id="volumeCargo">
            <div class="window__body window__body_info">
                <a href="#" class="window__close"></a>
                <div class="window__body__title">Объем груза</div>
                <div class="window__body__content">
                    <p>Объем груза необходимо знать, чтобы понимать, сколько транспорта понадобится для перевозки вашего груза, сколько места он займет на складе, какое оборудование понадобится для погрузки, а также учитывать другие особенности доставки, из которых складывается итоговая стоимость</p>
                </div>
            </div>
        </div>
        <div class="window" id="locationCargo">
            <div class="window__body window__body_info">
                <a href="#" class="window__close"></a>
                <div class="window__body__title">Информация о доставке</div>
                <div class="window__body__content">
                    <p>Информация о начальном и конечном пункте доставки груза позволит нашим менеджерам посчитать точную сумму доставки и предоставить вам подходящее по срокам и стоимости предложение.</p>
                </div>
            </div>
        </div>
        <div class="window" id="contactData">
            <div class="window__body window__body_info">
                <a href="#" class="window__close"></a>
                <div class="window__body__title">Контактные данные</div>
                <div class="window__body__content">
                    <p>Нам нужны ваши контактные данные, чтобы связаться по поводу вашей заявки, уточнить детали и предложить подходящие условия международной доставки.</p>
                </div>
            </div>
        </div>
        <div class="window" id="emptyCargoType">
            <div class="window__body window__body_info">
                <a href="#" class="window__close"></a>
                <div class="window__body__title">Ошибка рассчета</div>
                <div class="window__body__content">
                    <p>Необходимо указать тип груза для дальнейшего рассчета стоимости доставки</p>
                </div>
            </div>
        </div>
        <div class="window" id="errors">
            <div class="window__body window__body_info">
                <a href="#" class="window__close"></a>
                <div class="window__body__title">Ошибка отправки формы</div>
                <div class="window__body__content">
                </div>
            </div>
        </div>
        <div class="window" id="success">
            <div class="window__body window__body_info">
                <a href="#" class="window__close"></a>
                <div class="window__body__title">Расчет успешно отправлен</div>
                <div class="window__body__content">
                    Ваш расчет успешно отправлен. В ближайшее время с Вами свяжутся наши менеджеры для уточнения деталей доставки.
                </div>
            </div>
        </div>
        <div class="window" id="moreThanMax">
            <div class="window__body window__body_info">
                <a href="#" class="window__close"></a>
                <div class="window__body__title">Нужно указать больше?</div>
                <div class="window__body__content">
                    Чтобы вручную указать точный вес от 2000 кг до 20000кг, кликните по значению 2000 кг
                </div>
            </div>
        </div>
        <div class="window" id="cargoTypeIsEmty">
            <div class="window__body window__body_info">
                <a href="#" class="window__close"></a>
                <div class="window__body__title">Не указано содержимое груза</div>
                <div class="window__body__content">
                    Чтобы расчитать стоимость доставки, необходимо указать содержимое груза. <br><br>
                    В случае, если указанное содержимое груза не найдено, можете указать его вручную, а также заполнить остальные поля, в таком случае
                    заявка будет отправлена на составление расчета по вашему грузу.
                </div>
            </div>
        </div>



    <?}?>
<?}?>
<?php get_footer(); ?>
