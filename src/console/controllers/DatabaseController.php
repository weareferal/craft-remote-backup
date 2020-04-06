<?php

namespace weareferal\remotebackup\console\controllers;

use Craft;
use yii\console\Controller;
use yii\helpers\Console;
use yii\console\ExitCode;

use weareferal\remotebackup\RemoteBackup;

/**
 * Manage remote database backups
 */
class DatabaseController extends Controller
{
    public function requirePluginEnabled()
    {
        if (!RemoteBackup::getInstance()->getSettings()->enabled) {
            throw new \Exception('Remote Backup Plugin not enabled');
        }
    }

    public function requirePluginConfigured()
    {
        if (!RemoteBackup::getInstance()->getSettings()->configured()) {
            throw new \Exception('Remote Backup Plugin not correctly configured');
        }
    }

    /**
     * List remote database backups
     */
    public function actionList()
    {
        try {
            $this->requirePluginEnabled();
            $this->requirePluginConfigured();

            $results = RemoteBackup::getInstance()->remotebackup->listDatabases();
            if (count($results) <= 0) {
                $this->stdout("No remote database backups" . PHP_EOL, Console::FG_YELLOW);
            } else {
                $this->stdout("Remote database backups:" . PHP_EOL, Console::FG_GREEN);
                foreach ($results as $result) {
                    $this->stdout(" " . $result['value'] . PHP_EOL);
                }
            }
        } catch (\Exception $e) {
            Craft::$app->getErrorHandler()->logException($e);
            $this->stderr('Error: ' . $e->getMessage() . PHP_EOL, Console::FG_RED);
            return ExitCode::UNSPECIFIED_ERROR;
        }
        return ExitCode::OK;
    }

    /**
     * Create a remote database backup
     */
    public function actionCreate()
    {
        try {
            $this->requirePluginEnabled();
            $this->requirePluginConfigured();

            $filename = RemoteBackup::getInstance()->remotebackup->pushDatabase();
            $this->stdout("Created remote database backup:" . PHP_EOL, Console::FG_GREEN);
            $this->stdout(" " . $filename . PHP_EOL);
        } catch (\Exception $e) {
            Craft::$app->getErrorHandler()->logException($e);
            $this->stderr('Error: ' . $e->getMessage() . PHP_EOL, Console::FG_RED);
            return ExitCode::UNSPECIFIED_ERROR;
        }
        return ExitCode::OK;
    }

    /**
     * Delete old remote database backups
     */
    public function actionPrune()
    {
        try {
            $this->requirePluginEnabled();
            $this->requirePluginConfigured();

            if (!RemoteBackup::getInstance()->getSettings()->prune) {
                $this->stderr("Backup pruning disabled. Please enable via the Remote Backup control panel settings" . PHP_EOL, Console::FG_YELLOW);
                return ExitCode::CONFIG;
            } else {
                $filenames = RemoteBackup::getInstance()->remotebackup->pruneDatabases();
                if (count($filenames) <= 0) {
                    $this->stdout("No databases backups deleted" . PHP_EOL, Console::FG_YELLOW);
                } else {
                    $this->stdout("Deleted database backups:" . PHP_EOL, Console::FG_GREEN);
                    foreach ($filenames as $filename) {
                        $this->stdout(" " . $filename . PHP_EOL);
                    }
                }
                return ExitCode::OK;
            }
        } catch (\Exception $e) {
            Craft::$app->getErrorHandler()->logException($e);
            $this->stderr('Error: ' . $e->getMessage() . PHP_EOL, Console::FG_RED);
            return ExitCode::UNSPECIFIED_ERROR;
        }
    }
}
