<?php

return [

    'use_cdn' => env('USE_CDN', false),

    'cdn_url' => '',

    'filesystem' => [
        'disk' => 'asset-cdn',

        'options' => [
            //
        ],
    ],


    'webpack'   =>  [
        'location'  =>  env('CDN_WEBPACK_LOCATION'),
        'exclude'   =>  [
            //
        ]
    ],

    'files' => [
        'ignoreDotFiles' => true,

        'ignoreVCS' => true,

        'include' => [
            'paths' => [
                //
            ],
            'files' => [
                //
            ],
            'extensions' => [
                //
            ],
            'patterns' => [
                //
            ],
        ],

        'exclude' => [
            'paths' => [
                //
            ],
            'files' => [
                //
            ],
            'extensions' => [
                //
            ],
            'patterns' => [
                //
            ],
        ],
    ],

];
