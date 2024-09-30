<?php

use Arrilot\BitrixMigrations\Migrator;
use Arrilot\BitrixMigrations\Storages\WordpressDatabaseStorage;
use Arrilot\BitrixMigrations\TemplatesCollection;

class SimpleWpMigratorAjax
{
    /**
     * @return void
     */
    public function create_migration()
    {
        $migrator = $this->initMigrator();

        $result = ['success' => true];
        try {
            $migration = $migrator->createMigration(
                'Migration',
                'default',
            );

            $result['file'] = $migration;
        } catch (Exception $e) {
            $result = ['success' => false, 'message' => $e->getMessage()];
        }

        echo json_encode($result);
        die();
    }

    /**
     * @return void
     */
    public function run_migrations()
    {
        $migrator = $this->initMigrator();

        $result = ['success' => true];

        try {
            $migration = $migrator->runMigrations();

            $result['file'] = $migration;
        } catch (Exception $e) {
            $result = ['success' => false, 'message' => $e->getMessage()];
        }

        echo json_encode($result);
        die();
    }

    /**
     * @return void
     */
    public function rollback_migration()
    {
        $migrator = $this->initMigrator();

        $result = ['success' => true];

        try {
            $ran = $migrator->getRanMigrations();
            if (empty($ran)) {
                echo json_encode($result);
                die();
            }

            $migration = (string)$ran[count($ran) - 1];
            $migrator->removeSuccessfulMigrationFromLog($migration);
            // $migrator->deleteMigrationFile($migration);
        } catch (Exception $e) {
            $result = ['success' => false, 'message' => $e->getMessage()];
        }

        echo json_encode($result);
        die();
    }

    /**
     * @return Migrator
     */
    private function initMigrator() : Migrator
    {
        $pathMigrations = (string)get_option('simple_wp_migrator_migration_path', '/wp-content/plugins/simple-wp-migrator/migrations');
        if (!$pathMigrations) {
            $pathMigrations = '/wp-content/plugins/simple-wp-migrator/migrations';
        }

        $config = [
            'table' => 'simple_migrations',
            'dir' => $_SERVER['DOCUMENT_ROOT'] . $pathMigrations,
            'dir_archive' => './app/archive_migrations', // not required. default = "archive"
            'use_transaction' => false, // not required. default = false
            'default_fields' => []
        ];

        $database = new WordpressDatabaseStorage($config['table']);
        $templates = new TemplatesCollection();
        $templates->registerBasicTemplates();

        return new Migrator($config, $templates, $database);
    }
}