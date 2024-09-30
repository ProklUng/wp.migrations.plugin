<?php

use Arrilot\BitrixMigrations\BaseMigrations\WordpressMigration;
use Arrilot\BitrixMigrations\Storages\FileStorage;
use Arrilot\BitrixMigrations\Storages\WordpressDatabaseStorage;

if (!function_exists('simple_wp_migrator_runner_html')) {
    function simple_wp_migrator_runner_html() : string
    {
        $installed = class_exists(WordpressDatabaseStorage::class);
        if (!$installed) {
            return '<div>Structure: fail</div>';
        }

        $database = new WordpressDatabaseStorage('simple_migrations');
        if (!$database->checkMigrationTableExistence()) {
            $database->createMigrationTable();
        }

        $html = '';
        $storage = new WordpressDatabaseStorage('simple_migrations');
        $fileStorage = new FileStorage();

        $pathMigrations = trim((string)get_option('simple_wp_migrator_migration_path', '/wp-content/plugins/simple-wp-migrator/migrations'));
        if (!$pathMigrations) {
            $pathMigrations = '/wp-content/plugins/simple-wp-migrator/migrations';
        }

        $migrations = $fileStorage->getMigrationFiles($_SERVER['DOCUMENT_ROOT'] . $pathMigrations);

        $runMigrations = $storage->getRanMigrations();
        $html = $html . '<section style="margin-top: 16px">';

        foreach ($migrations as $fileName) {
            if (in_array($fileName, $runMigrations)) {
                $html = $html . "<div><span style='display:inline-block;width:280px'>$fileName</span> <span style='color:darkseagreen'>runned successfully</span></div>";
            } else {
                $html = $html . "<div><span style='display:inline-block;width:280px'>$fileName</span> <span style='color:indianred'>not runned</span></div>";
            }

        }

        $html = $html . '</section>';

        return $html;
    }
}

if (!function_exists('simple_wp_migrator_buttons_html')) {
    function simple_wp_migrator_buttons_html() : string
    {
      $html = '<button class="cncl-button cncl-button_primary" type="button" name="create_migration" onclick="adminMigrationsAjaxHandler(createMigration, this); return false">
            Создать миграцию
            </button>'
            .
          '<button style="margin-left:8px" class="cncl-button cncl-button_primary" type="button" name="run_migrations" onclick="adminMigrationsAjaxHandler(runMigrations, this); return false">
            Выполнить миграции
            </button>'
           .
          '<button  style="margin-left:8px" class="cncl-button cncl-button_primary" type="button" name="create_migration" onclick="adminMigrationsAjaxHandler(rollbackMigration, this); return false">
            Откатить последнюю
            </button>'
      ;

        return $html;
    }
}

if (!function_exists('simple_wp_migrator_result_migrations_html')) {
    function simple_wp_migrator_result_migrations_html() : string
    {
        $html = '<div id="result_migrations"></div>';

        return $html;
    }
}