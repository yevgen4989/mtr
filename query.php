<?php
$arr = array(
    'post_type' => 'leads',
    'date_query' => [
        [
            'after'    => [
                'year'  => 2021,
                'month' => 3,
                'day'   => 1,
            ],
            'before'    => [
                'year'  => 2021,
                'month' => 2,
                'day'   => 1,
            ],
            'inclusive' => true,
        ],
    ],
);
