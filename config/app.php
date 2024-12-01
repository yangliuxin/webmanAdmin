<?php

use support\Request;

return [
    'enable' => true,
    'debug' => true,
    'controller_suffix' => 'Controller',
    'controller_reuse' => false,
    'version' => '1.0.0',
    'statistics' => [
        'show' => true,
        'data' => [
            [
                'icon' => 'ion-ios-gear-outline',
                'class' => 'bg-aqua',
                'title' => '占位符1',
                'data' => 0
            ],
            [
                'icon' => 'ion-android-mail',
                'class' => 'bg-red',
                'title' => '占位符2',
                'data' => 0
            ],
            [
                'icon' => 'ion-ios-cart-outline',
                'class' => 'bg-green',
                'title' => '占位符3',
                'data' => 0
            ],
            [
                'icon' => 'ion-ios-people-outline',
                'class' => 'bg-yellow',
                'title' => '占位符4',
                'data' => 0
            ]
        ],
    ]
];
