<?php
get_header();


$trackNumber = $_POST['track_number'] ?? null;
$trackStatuses = null;
$createdOrderDate = '';
$message = '';
$arTrackStatuses = array();

if (empty($trackNumber) || $trackNumber == null) {
    $message = 'Не указан номер заявки.';
}
else {
    $trackStatuses = new WP_Query([
        'post_type' => 'track_statuses',
        'title' => $trackNumber,
        'orderby' => 'ID',
        'order'   => 'ASC',
    ]);
    foreach ($trackStatuses->posts as $status){
        $status = (array)$status;
        $status['acf'] = get_fields($status['ID']);
        $arTrackStatuses[] = $status;
    }
    $amount = $trackStatuses->post_count;
    
    if ($amount > 0) {
        $createdOrderDate = get_the_date('d.m.Y',$trackStatuses->posts[0]->ID);
    } else {
        $message = 'Заявки с таким номером не найдено.';
    }
}

$result = [
    'track_number' => $trackNumber,
    'trackStatuses' => $arTrackStatuses,
    'createdOrderDate' => $createdOrderDate,
    'message' => $message,
];

?>
<div class="content">
    <div class="content-grid">
        <div class="content-grid__item content-grid__item_twice content-grid__item_show">
            <div class="panel panel__as-announcement-item panel_nmargin">
                <div class="tracking__wrapper">
                    <?if(empty($result['message'])){?>
                        <div class="tracking__title">
                            <h1>Груз <?=$result['track_number']?></h1>
                            <div class="tracking__subtitle">Заявка принята в работу: <?=$result['createdOrderDate']?></div>
                        </div>
                        <div class="tracking__list">
                            <?if(count($result['trackStatuses']) > 0){
                                foreach ($result['trackStatuses'] as $status){?>
                                    <div class="tracking__item">
                                        <div class="tracking__item__history"></div>
                                        <div class="tracking__item__description">
                                            <div class="tracking__item__description__date"><?=get_the_date('d F Y', $status['ID'])?></div>
                                            <div class="tracking__item__description__status"><?=$status['acf']['status']->post_title?></div>
                                        </div>
                                    </div>
                                <?}
                            }?>
                        </div>
                        <div class="tracking__note">
                            За подробной информацией о местонахождении груза, обратитесь к Вашему менеджеру
                        </div>
                    <?}else{
                        echo $result['message'];
                    }?>
                </div>
            </div>
        </div>
        <div class="content-grid__item content-grid__item_nmargin content-grid__item__once content-grid__item_no-background content-grid__item_clear content-grid__item_show">
            <form class="action-form" action="/tracking/" id="re_check_tracknumber" method="POST">
                <div class="form-group__label">Проверить статус других заявок</div>
                <div class="form-group">
                    <input type="text" name="track_number" class="form-control form-control_margin" placeholder="Номер заявки">
                </div>
                <div class="form-group">
                    <a href="#" class="button button_success button_as-input">Проверить статус</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
get_footer();
?>
