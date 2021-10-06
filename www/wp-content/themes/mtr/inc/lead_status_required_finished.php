<?php
function replaceTagsForStatusesForLeads($lead_id , $status_id, $template){
    $template = str_replace('{current_manager_name}', get_user_by('ID',get_option('current_manager'))->display_name, $template);
    $template = str_replace('{lead_id}', $lead_id, $template);
    $template = str_replace('{lead_status_name}', get_post($status_id)->post_title, $template);

    return $template;
}

add_action( 'LeadChangeStatusRequired', 'LeadStatusRequiredFinishedHandler' );
function LeadStatusRequiredFinishedHandler() {
    define( "SECONDS_PER_DAY", 86400 );

    $statusesRequiredToChange = new WP_Query([
        'post_type' => 'statuses_for_leads',
        'posts_per_page' => -1,
        'orderby' => 'id',
        'order'=>'ASC',
        'post_status' => 'publish',
        'meta_query' => [
            'relation' => 'AND',
            [
                'key' => 'status',
                'value' => 1
            ],
            [
                'key' => 'required_to_complete',
                'value' => 1
            ]
        ]
    ]);
    $statusesRequiredToChange = $statusesRequiredToChange->posts;

    if(count($statusesRequiredToChange) == 0) return;

    $currentDate = new DateTime();

    foreach($statusesRequiredToChange as $status) {
        $leadsInStatus = new WP_Query([
            'post_type' => 'leeds',
            'posts_per_page' => -1,
            'orderby' => 'id',
            'order'=>'ASC',
            'post_status' => 'publish',
            'meta_query' => [
                [
                    'key' => 'status',
                    'value' => $status->ID
                ]
            ]
        ]);
        $leadsInStatus = $leadsInStatus->posts;

        foreach ( $leadsInStatus as $lead ) {
            $daysToChange      = get_field('days_to_complete', $status->ID);

            $updatedDateTimestamp = new DateTime($lead->post_modified);
            $updatedDateTimestamp = $updatedDateTimestamp->modify('+'.$daysToChange.' days');


            if ( $updatedDateTimestamp->getTimestamp() <= $currentDate->getTimestamp() ) {
                $lead->post_modified = $currentDate->format( 'Y-m-d H:i:s' );
                wp_update_post( wp_slash($lead) );

                $data = [
                    'comment_post_ID'      => $lead->ID,
                    'comment_author'       => 'WP_WORKER',
                    'comment_author_email' => '',
                    'comment_author_url'   => '',
                    'comment_content'      => 'WP_WORKER: отправка уведомления "Необходима обработка лида №' . $lead->ID.'"',
                    'comment_type'         => 'comment',
                    'comment_parent'       => 0,
                    'user_id'              => 1,
                    'comment_author_IP'    => '',
                    'comment_agent'        => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.10) Gecko/2009042316 Firefox/3.0.10 (.NET CLR 3.5.30729)',
                    'comment_date'         => null,
                    'comment_approved'     => 1,
                ];
                wp_insert_comment( wp_slash($data) );

                //MAIL
                $mailEvents = new WP_Query( [
                    'post_type'     => 'notification',
                    'post_status'   => 'draft',
                    'posts_per_page'=> -1,

                ]);
                foreach ($mailEvents->posts as $event){
                    $event_content = json_decode($event->post_content, true);

                    if($event_content['enabled'] == false && $event_content['trigger'] == 'post/statuses_for_leads/published'){
                        $subject = $event_content['carriers']['email']['subject'];
                        $message = replaceTagsForStatusesForLeads($lead->ID, $status->ID, $event_content['carriers']['email']['body']);

	                    $event_content['carriers']['email']['recipients'][] = array(
		                    'recipient' => get_user_by('ID',get_option('current_manager'))->user_email
	                    );

	                    $headers = "MIME-Version: 1.0". "\r\n";;
	                    $headers .= "Content-type: text/html; charset=iso-8859-1"."\r\n";
	                    $headers .= "From: MTRGROUP-SYSTEM <".get_option('admin_email').">";

	                    foreach ($event_content['carriers']['email']['recipients'] as $recipient){
		                    mail( $recipient['recipient'], $subject.' №'.$lead->ID, $message, $headers );
	                    }
                    }
                }


	            error_log('LeadChangeStatusRequired cron event finished');
            }



        }
    }
}
