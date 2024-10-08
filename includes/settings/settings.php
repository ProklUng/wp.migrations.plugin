<?php
require_once 'functions.php';

$fields = [
    [
        'type'    => 'text',
        'id'      => 'migration_path',
        'label'   => __( 'Путь к миграциям', 'simple-wp-migrator' ),
        'desc'    => ' ',
        'default' => '/wp-content/plugins/simple-wp-migrator/migrations',
        'props'   => [

        ],
    ],

    [
        'type'    => 'html',
        'id'      => 'buttons_migration',
        'default' => '',
        'content' => simple_wp_migrator_buttons_html(),
        'label' => ' '
    ],

    [
        'type'    => 'html',
        'id'      => 'run_migrations',
        'default' => '',
        'content' => simple_wp_migrator_runner_html(),
        'label' => ' '
    ],

    [
        'type'    => 'html',
        'id'      => 'result_migration',
        'default' => '',
        'content' => simple_wp_migrator_result_migrations_html(),
        'label' => ' '
    ],
];

return $fields;