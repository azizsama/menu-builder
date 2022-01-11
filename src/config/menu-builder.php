<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Menu to build
    |--------------------------------------------------------------------------
    | This is the list of the menu items to build.
    | You must group the menu items by their group name.
    | The group name is the key of the array.
    | The menu items are the value of the array.
    | This is the example of the menu to build:
    | [
    |     'main' => [
    |         [
    |             'name' => 'Home',
    |             'url' => '/',
    |             'active' => 'home',
    |             'children' => [
    |                 [
    |                     ...
    |                 ],
    |             ],
    |         ],
    |         [
    |             'name' => 'Users',
    |             'url' => '/users',
    |             'icon' => 'users',
    |             'permission' => 'users',
    |             'active' => 'users',
    |         ],
    |     ],
    |     'sidebar' => [
    |         ...
    |     ],
    | ]
    |--------------------------------------------------------------------------
     */
    'menu' => [
        'main' => [
            [
                'text' => 'Home',
                'url' => '/',
                'target' => '',
                'active' => [
                    '/',
                ],
            ],
        ],
        'sidebar' => [
            [
                'text' => 'Manga',
                'icon' => 'BookOpen',
                'target' => '',
                'active' => [
                    '/admin/manga',
                ],
                'childs' => [
                    [
                        'text' => 'List',
                        'url' => '/admin/manga',
                        'target' => '',
                        'active' => [
                            '/admin/manga',
                        ],
                        'icon' => 'MenuAlt4'
                    ],
                    [
                        'text' => 'Create',
                        'url' => '/admin/manga/create',
                        'target' => '',
                        'active' => [
                            '/admin/manga/create',
                        ],
                        'icon' => 'Plus',
                    ],
                    [
                        'text' => 'Chapters',
                        'target' => '',
                        'active' => [
                            '/admin/manga/chapters',
                        ],
                        'icon' => 'BookOpen',
                        'childs' => [
                            [
                                'text' => 'Create',
                                'url' => '/admin/manga/chapter/create',
                                'target' => '',
                                'active' => [
                                    '/admin/manga/chapter/create',
                                ],
                                'icon' => 'Plus',
                            ]
                        ]
                    ],
                ]
            ],
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Menu filters
    |--------------------------------------------------------------------------
    | This is the list of the filters to apply to the menu.
    | You can add your own filters here by adding the class name of the filter.
    |--------------------------------------------------------------------------
    */
    'filters' => [
        // 'AzizSama\MenuBuilder\Filters\ActiveFilter',
        // 'AzizSama\MenuBuilder\Filters\PermissionFilter',
    ],
];
