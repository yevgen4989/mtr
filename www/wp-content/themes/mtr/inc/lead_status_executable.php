<?php
function replaceTagsForLeadsCRON($post_id, $minutes, $template){

    $arPost = get_post($post_id);
    $arAcf = get_fields($post_id);

    $template = str_replace('{post_ID}', $arPost->ID, $template);
    $template = str_replace('{home_url}', home_url(), $template);
    $template = str_replace('{leads_modification_datetime}', $arPost->post_modified, $template);
	$template = str_replace('{leads_creation_datetime}', $arPost->post_date, $template);

	if(!empty($arAcf['status'])){
		$template = str_replace('{status}', $arAcf['status']->post_title, $template);
	}
	else{
		$template = str_replace('{status}', 'Недоступно', $template);
	}

	if(!empty($arAcf['from_country'])){
		$template = str_replace('{from_country}', $arAcf['from_country']->post_title, $template);
	}
	else{
		$template = str_replace('{from_country}', 'Недоступно', $template);
	}

	if(!empty($arAcf['from_storage'])){
		$template = str_replace('{from_storage}', $arAcf['from_storage']->post_title, $template);
	}
	else{
		$template = str_replace('{from_storage}', 'Недоступно', $template);
	}

	if(!empty($arAcf['delivery_route'])){
		$template = str_replace('{delivery_route}', $arAcf['delivery_route']->post_title, $template);
	}
	else{
		$template = str_replace('{delivery_route}', 'Недоступно', $template);
	}

	if(!empty($arAcf['delivery_route'])){
		$template = str_replace('{delivery_route}', $arAcf['delivery_route']->post_title, $template);
	}
	else{
		$template = str_replace('{delivery_route}', 'Недоступно', $template);
	}

	if(!empty($arAcf['cargo_type'])){
		$template = str_replace('{cargo_type}', $arAcf['cargo_type']->post_title, $template);
	}
	else{
		$template = str_replace('{cargo_type}', 'Недоступно', $template);
	}

	if(!empty($arAcf['manager'])){
		$template = str_replace('{manager_name}', $arAcf['manager']['display_name'], $template);
	}
	else{
		$template = str_replace('{manager_name}', 'Недоступно', $template);
	}


    $template = str_replace('{from_city}', $arAcf['from_city'] ?? '', $template);
    $template = str_replace('{to_city}', $arAcf['to_city'] ?? '', $template);
    $template = str_replace('{estimated_shipping_cost}', $arAcf['estimated_shipping_cost'] ?? '', $template);
    $template = str_replace('{client_name}', $arAcf['client_name'] ?? '', $template);
    $template = str_replace('{phone}', $arAcf['phone_text'] ?? '', $template);
    $template = str_replace('{email}', $arAcf['email'] ?? '', $template);
    $template = str_replace('{cargo_volume}', $arAcf['cargo_volume'] ?? '', $template);
    $template = str_replace('{cargo_weight}', $arAcf['cargo_weight'] ?? '', $template);
    $template = str_replace('{cargo_type_client}', $arAcf['cargo_type_client'] ?? '', $template);

	if(!empty($arAcf['parametrs'])){
		$template = str_replace('{name_variable}', $arAcf['parametrs']['name_variable'], $template);
		$template = str_replace('{count_variable}', $arAcf['parametrs']['count_variable'], $template);
	}
	else{
		$template = str_replace('{name_variable}', 'Нет данных', $template);
		$template = str_replace('{count_variable}', 'Нет данных', $template);
	}


    $template = str_replace('{need_container}', $arAcf['need_container'] ? 'Да' : 'Нет', $template);
    $template = str_replace('{referer}', $arAcf['referer'], $template);



	if($minutes != null){
		$template = str_replace('{minutes}', $minutes, $template);
	}else{
		$template = str_replace('{minutes}', 'Нет данных', $template);
	}


    return $template;
}


add_action( 'LeadStatusExecutable', 'LeadStatusExecutableHandler' );
function LeadStatusExecutableHandler() {

    $timeNow = new DateTime();
    $dayWeek = $timeNow->format('l');
    $dayWeek = strtolower($dayWeek);

    $info = get_fields('info');

    if($dayWeek == 'saturday' || $dayWeek == 'sunday'){
        $arData = $info['weekends'][$dayWeek];
    }
    else{
        $arData = $info[$dayWeek];
    }

    if($arData['activity'] == 1){
        $begin = new DateTime($arData['start_work']);
        $end = new DateTime($arData['end_work']);

        if ($timeNow >= $begin && $timeNow <= $end) {


            $leadStatuses = new WP_Query([
                'post_type' => 'statuses_for_leads',
                'posts_per_page' => 1,
                'orderby' => 'id',
                'order'=>'ASC',
                'meta_query' => [
                    'relation' => 'AND',
                    [
                        'key' => 'status',
                        'value' => 1
                    ],
                    [
                        'key' => 'final_status',
                        'value' => 0
                    ]
                ]
            ]);
            $leadStatusNew = $leadStatuses->post;

            $leadStatuses = new WP_Query([
                'post_type' => 'statuses_for_leads',
                'posts_per_page' => -1,
                'orderby' => 'id',
                'order'=>'ASC',
                'meta_query' => [
                    'relation' => 'AND',
                    [
                        'key' => 'status',
                        'value' => 1
                    ],
                    [
                        'key' => 'final_status',
                        'value' => 0
                    ]
                ]
            ]);
            $leadStatuses = $leadStatuses->posts;



            foreach ($leadStatuses as $leadStatus) {

                $leadsInProgress = new WP_Query([
                    'post_type' => 'leads_in_process',
                    'posts_per_page' => -1,
                    'orderby' => 'id',
                    'order'=>'ASC',
                    'meta_query' => [
                        'relation' => 'AND',
                        [
                            'key' => 'status',
                            'value' => $leadStatus->ID
                        ],
                        [
                            'key' => 'end_executable',
                            'compare' => 'NOT EXISTS',
                            'value' => 'null',
                        ]
                    ]
                ]);
                $leadsInProgress = $leadsInProgress->posts;

                //id всех лидов в статусе Новый, которые в данный момент находятся в обработке (значит у них выставлен менеджер)
                $newLeadsInProgressIds = [];

                foreach ($leadsInProgress as $leadInProgress) {
                    //соберем все идентификаторы лидов, которые сейчас в статусе Новый и находятся в процессе работы
                    if ($leadStatus->ID == $leadStatusNew->ID) {
                        //статус у лида соответствует статусу Новый и данному лиду уже назначен менеджер, запомним его
                        $newLeadsInProgressIds[] = get_post_meta($leadInProgress->ID, 'lead', true);
                    }

                    //статус не конечен, значит надо проводить работы по соотношению каждого из текущих статусов
                    //так же нам надо проверить дату окончания статуса, и его конечности, после поставить статус
                    //финальный или не финальный на момент проверки

                    checkChain(get_post_meta($leadInProgress->ID, 'lead', true), $leadStatus, $timeNow);
                }

                //Нужно проверить, нет ли заявок в статусе "Новый", у которых не установлен менеджер и прошел срок выполнения
                //Log::error('leadInProgress checking is finished');
                if ($leadStatus->ID == $leadStatusNew->ID) {
                    //выберем все лиды со статусом "Новый" у которых не установлен пользователь и по которым не ведется
                    //отслеживание выполнения статусов
                    $leads = (new WP_Query([
                        'post_type' => 'leads',
                        'posts_per_page' => -1,
                        'orderby' => 'id',
                        'order'=>'ASC',
                        'exclude' => $newLeadsInProgressIds,
                        'meta_query' => [
                            'relation' => 'AND',
                            [
                                'key' => 'status',
                                'value' => $leadStatusNew->ID
                            ],
                            [
                                'key' => 'manager',
                                'compare' => 'NOT EXISTS',
                                'value' => 'null',
                            ]
                        ]
                    ]))->posts;

                    $currentTime = $timeNow->getTimestamp();

                    foreach ($leads as $lead) {
                        $createdAt = (new DateTime($lead->post_date_gmt))->getTimestamp();
                        if ((($currentTime - $createdAt) / 60) > get_post_meta($leadStatusNew->ID, 'time_complete', true)) {
                            //время обработки нового лида просрочено, приступаем к распределению менеджера
                            distributionLead($lead->ID);
                        }
                    }
                }
            }

			error_log('LeadStatusExecutableHandler cron event');
        }
    }

}

function checkChain($lead_id, $leadStatus, DateTime $timeNow) {
    /**
     * Проверим, находится ли текущий менеджер на обеденном перерыве
     */

    $lead = get_post($lead_id);
    $manager = get_field('manager', $lead_id)['ID'];
    $lunchBreak = get_field('break','user_'. $manager);
    if ($lunchBreak == 1) {
        //Менеджер все еще на обеде, не будем обрабатывать его статусы
        return;
    }

    $leadsStatuses = (new WP_Query([
        'post_type' => 'leads_in_process',
        'posts_per_page' => -1,
        'orderby' => 'id',
        'order'=>'ASC',
        'meta_query' => [
            'relation' => 'AND',
            [
                'key' => 'lead',
                'value' => $lead_id
            ],
            [
                'key' => 'end_executable',
                'compare' => 'NOT EXISTS',
                'value' => 'null',
            ]
        ]
    ]))->posts;

    $first = $leadsStatuses[0];
    foreach ($leadsStatuses as $leadsStatus) {
        if ($leadsStatus->ID == $first->ID) {
            continue;
        }

        if (empty(get_post_meta($first->ID, 'end_executable', true))) {
            update_field('end_executable',get_post_meta($leadsStatus->ID, 'start_executable', true), $first->ID,);

            if (!get_post_meta($first->ID, 'is_success_executable', true)) {
                //проверяем на выполнение
                $minutesToExecute =  get_post_meta($leadStatus->ID, 'time_complete', true);
                $timeStart = (new DateTime(get_post_meta($first->ID, 'start_executable', true)))->getTimestamp();
                $timeEnd = (new DateTime(get_post_meta($first->ID, 'end_executable', true)))->getTimestamp();

                if ((($timeEnd - $timeStart) / 60) <= $minutesToExecute) {
                    update_field('is_success_executable', 1, $first->ID);
                }
            }
        }

        $first = $leadsStatus;
    }

    //Сейчас мы должны проверить последний статус на время завершения лида
    $minutesToExecute = get_post_meta($leadStatus->ID, 'time_complete', true);
    $timeStart = (new DateTime(get_post_meta($first->ID, 'start_executable', true)))->getTimestamp();
    $timeEnd = $timeNow->getTimestamp();
    $substraction = round($minutesToExecute - (($timeEnd - $timeStart) / 60));
    switch ($substraction) {
        case 60:
            //отправляем сообщение с предупреждением менеджеру
            sendMail(get_post_meta($first->ID, 'lead', false), 60);
            break;
        case 30:
            //отправляем сообщение с предупреждением менеджеру
            sendMail(get_post_meta($first->ID, 'lead', false), 30);
            break;
        case 15:
            //отправляем сообщение с предупреждением менеджеру
            sendMail(get_post_meta($first->ID, 'lead', false), 15);
            break;
        case 5:
            //отправляем сообщение с предупреждением менеджеру
            sendMail(get_post_meta($first->ID, 'lead', false), 5);
            break;
        case 0:
            sendMail(get_post_meta($first->ID, 'lead', false), 0);
            break;
    }
}

function distributionLead($lead_id) {

    $lead = get_post($lead_id);
    $managers = get_users( array(
        'role'    => 'manager',
        'orderby' => 'user_nicename',
        'order'   => 'ASC'
    ) );
    //нужно взять таблицу замещения на текущую дату и если текущший менеджер имеет замену, то убрать этого менеджера из списка распределения
    $currentDate = (new DateTime());

    $managersForDistribution = [];
    foreach ($managers as $manager) {
        $substitution = (new WP_Query([
            'post_type' => 'manager_actions',
            'posts_per_page' => 1,
            'orderby' => 'id',
            'order'=>'ASC',
            'date_query'        => array(
                array(
                    'year'  => $currentDate->format('Y'),
                    'month' => $currentDate->format('m'),
                    'day'   => $currentDate->format('d')
                )
            ),
            'meta_query' => [
                [
                    'key' => 'current_manager',
                    'value' => $manager->ID
                ],
            ]
        ]))->post;
        if (!empty($substitution)) {
            continue;
        }

        $substitutionManagers = (new WP_Query([
            'post_type' => 'substitution',
            'posts_per_page' => 1,
            'orderby' => 'id',
            'order'=>'ASC',
            'meta_query' => [
                [
                    'key' => 'user_id',
                    'value' => $manager->ID
                ],
            ]
        ]))->post;

        if (empty($substitutionManagers)) {
            //записи о назначении лидов нет значит нужно добавить данную запись и обнулить счетчик
            $substitution_managers_post = wp_insert_post([
                'post_type'     => 'substitution',
                'post_status'   => 'publish',
                'post_author'   => 1,
            ]);
            update_field('user_id', $manager->ID, $substitution_managers_post);
            update_field('amount_leads', 0, $substitution_managers_post);


            $substitutionManagers = (new WP_Query([
                'post_type' => 'substitution',
                'posts_per_page' => 1,
                'orderby' => 'id',
                'order'=>'ASC',
                'meta_query' => [
                    [
                        'key' => 'user_id',
                        'value' => $manager->ID
                    ],
                ]
            ]))->post;
        }
        $managersForDistribution[$manager->ID] = get_post_meta($substitutionManagers->ID,'amount_leads', true);
    }

    //Нужно отсортировать массив $managersForDistribution в порядке увеличения распределенных задач и
    //выбрать менеджера с наименьшим количеством заявок
    $minimalUserId = null;
    $minimalAmountLeads = null;
    foreach($managersForDistribution as $userId => $amountLeads) {
        if (is_null($minimalUserId)) {
            $minimalUserId = $userId;
            $minimalAmountLeads = $amountLeads;
        } else {
            if ($minimalAmountLeads > $amountLeads) {
                $minimalAmountLeads = $amountLeads;
                $minimalUserId = $userId;
            }
        }
    }

    if (!empty($minimalUserId)) {
        $user = get_user_by('ID',$minimalUserId);
        $firstLeadStatus = (new WP_Query([
            'post_type' => 'statuses_for_leads',
            'posts_per_page' => 1,
            'orderby' => 'id',
            'order'=>'ASC',
            'meta_query' => [
                [
                    'key' => 'status',
                    'value' => 1
                ],
            ]
        ]))->post;
        $leadStatusNext = (new WP_Query([
            'post_type' => 'statuses_for_leads',
            'posts_per_page' => 1,
            'orderby' => 'id',
            'order'=>'ASC',
            'exclude' => $firstLeadStatus->ID,
            'meta_query' => [
                [
                    'key' => 'status',
                    'value' => 1
                ],
            ]
        ]))->post;


        update_field('manager',$user->ID, $lead->ID);
        if (!empty($leadStatusNext)) {
            update_field('status',$leadStatusNext->ID, $lead->ID);
        }
        ++$minimalAmountLeads;

        $substitutionManager = (new WP_Query([
            'post_type' => 'substitution',
            'posts_per_page' => 1,
            'orderby' => 'id',
            'order'=>'ASC',
            'meta_query' => [
                [
                    'key' => 'user_id',
                    'value' => $minimalUserId
                ],
            ]
        ]))->post;

        if(!empty($substitutionManager)){
            update_field('user_id', $minimalUserId, $substitutionManager->ID);
            update_field('amount_leads', $minimalAmountLeads, $substitutionManager->ID);
        }
        else{
            $substitution_managers_post = wp_insert_post([
                'post_type'     => 'substitution',
                'post_status'   => 'publish',
                'post_author'   => 1,
            ]);
            update_field('user_id', $minimalUserId, $substitution_managers_post);
            update_field('amount_leads', $minimalAmountLeads, $substitution_managers_post);
        }

        //MAIL
        $mailEvents = new WP_Query( [
            'post_type'     => 'notification',
            'post_status'   => 'draft',
            'posts_per_page'=> -1,

        ]);
        foreach ($mailEvents->posts as $event){
            $event_content = json_decode($event->post_content, true);

            if($event_content['enabled'] == false && $event_content['trigger'] == 'post/manager_actions/published'){
                $subject = $event_content['carriers']['email']['subject'];
                $message = replaceTagsForLeadsCRON($lead->ID, null, $event_content['carriers']['email']['body']);

	            $event_content['carriers']['email']['recipients'][] = array(
		            'recipient' => $user->user_email
	            );

	            $headers = "MIME-Version: 1.0". "\r\n";;
	            $headers .= "Content-type: text/html; charset=iso-8859-1"."\r\n";
	            $headers .= "From: MTRGROUP-SYSTEM <".get_option('admin_email').">";

	            foreach ($event_content['carriers']['email']['recipients'] as $recipient){

		            mail( $recipient['recipient'], $subject, $message, $headers );

	            }
            }
        }

    }
}

function sendMail($lead_id, $minutes) {
    $lead = get_post($lead_id);

    if(!empty($lead_id) && !empty($lead)){
        //MAIL
        $mailEvents = new WP_Query( [
            'post_type'     => 'notification',
            'post_status'   => 'draft',
            'posts_per_page'=> -1,

        ]);
        foreach ($mailEvents->posts as $event){
            $event_content = json_decode($event->post_content, true);

            if($event_content['enabled'] == false && $event_content['trigger'] == 'post/leads_in_process/published'){
                $subject = $event_content['carriers']['email']['subject'].' №' . $lead_id . ' в статусе ' . get_field('status', $lead_id)->post_title;
                $message = replaceTagsForLeadsCRON($lead->ID, $minutes, $event_content['carriers']['email']['body']);


	            $event_content['carriers']['email']['recipients'][] = array(
		            'recipient' => get_user_by('ID', get_field('manager', $lead_id))->user_email
	            );

	            $headers = "MIME-Version: 1.0". "\r\n";;
	            $headers .= "Content-type: text/html; charset=iso-8859-1"."\r\n";
	            $headers .= "From: MTRGROUP-SYSTEM <".get_option('admin_email').">";

	            foreach ($event_content['carriers']['email']['recipients'] as $recipient){
		            mail( $recipient['recipient'], $subject, $message, $headers );

	            }
            }
        }
    }
}
