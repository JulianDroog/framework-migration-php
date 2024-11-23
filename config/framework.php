<?php


// maybe add a version in the future, but probably make something like rector with Laravel11, cakephp5 sets or something

return [
    'laravel' => [
        'migrations' => 'database/migrations',
        'seeds' => 'database/seeds'
    ],
    'cakephp' => [
        'migrations' => 'config/Migrations',
        'seeds' => 'config/Seeds'
    ],
];
