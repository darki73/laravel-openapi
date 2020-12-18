<?php

return [

    'api'                                   =>  [
        'title'                             =>  env('API_NAME', 'Application API Documentation'),
        'description'                       =>  env('API_DESCRIPTION', 'Documentation for Application API'),
        'version'                           =>  env('API_VERSION', env('APP_VERSION', '1.0.0')),
        'host'                              =>  env('API_HOST', 'http://localhost'),
        'path'                              =>  env('API_PATH', '/documentation'),
        'tos'                               =>  env('API_TOS_URL', null),
    ],

    'contact'                               =>  [
        'name'                              =>  env('API_CONTACT_NAME', null),
        'email'                             =>  env('API_CONTACT_EMAIL', null),
        'url'                               =>  env('API_CONTACT_URL', null),
    ],

    'license'                               =>  [
        'name'                              =>  env('API_LICENSE_NAME', null),
        'url'                               =>  env('API_LICENSE_URL', null)
    ],

    'generated'                             =>  false,

    'storage'                               =>  env('API_STORAGE', storage_path('openapi')),

    'views'                                 =>  base_path('resources/views/vendor/openapi'),

    'translations'                          =>  base_path('resources/lang/vendor/openapi'),

    'servers'                               =>  [
//        'http://localhost',
//        [
//            'url'                           =>  'http://localhost',
//            'description'                   =>  'Demo Server'
//        ],
//        [
//            'url'                           =>  'http://{username}.localhost:{port}/{basePath}',
//            'description'                   =>  'Demo Server with variables',
//            'variables'                     =>  [
//                'username'                  =>  [
//                    'default'               =>  'laravel',
//                    'description'           =>  'These variables will not be validated, so take caution when adding them'
//                ],
//                'port'                      =>  [
//                    'enum'                  =>  [
//                        8443,
//                        443
//                    ],
//                    'default'               =>  8443
//                ],
//                'basePath'                  =>  [
//                    'default'               =>  'v2'
//                ]
//            ]
//        ]
    ],

    'ignored'                               =>  [
        'methods'                           =>  [
            'head'
        ],
        'packages'                          =>  [
            'ignition'                      =>  true,
            'passport'                      =>  true,
            'sanctum'                       =>  true,
        ],
        'routes'                            =>  [
            'openapi.ui',
            'openapi.content',
            'openapi.content.json',
            'openapi.content.yaml',
        ]
    ],

    'authentication_flow'                   =>  [
        'OAuth2'                            =>  'authorizationCode',
        'bearerAuth'                        =>  'http'
    ]
];
