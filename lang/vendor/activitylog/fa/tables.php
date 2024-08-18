<?php

return [
    'columns' => [
        'log_name' => [
            'label' => 'نوع',
        ],
        'event' => [
            'label' => 'رخداد',
        ],
        'subject_type' => [
            'label' => 'موضوع',
        ],
        'causer' => [
            'label' => 'کاربر',
        ],
        'properties' => [
            'label' => 'خواص',
        ],
        'created_at' => [
            'label' => 'خارج شده در',
        ],
    ],
    'filters' => [
        'created_at' => [
            'label'         => 'خارج شده در',
            'created_from'  => 'ایجاد شده از ',
            'created_until' => 'ایجاد شده تا ',
        ],
        'event' => [
            'label' => 'رخداد',
        ],
    ],
];
