<?php

/**
 * test plugin for Craft CMS 3.x
 *
 * test
 *
 * @link      test.com
 * @copyright Copyright (c) 2019 test
 */

namespace weareferal\remotebackup\console\controllers;

use weareferal\remotebackup\Test;

use Craft;
use yii\console\Controller;
use yii\helpers\Console;
use yii\console\ExitCode;

use weareferal\remotebackup\RemoteBackup;

/**
 * Backup database backups
 *
 * @author    test
 * @package   Test
 * @since     1
 */
class DatabaseController extends Controller
{
    /**
     * Create a local database backup
     */
    public function actionCreate()
    {
        try {
            $path = RemoteBackup::getInstance()->remotebackup->createDatabaseBackup();
            $this->stdout("Created database backup: " . $path . PHP_EOL, Console::FG_GREEN);
        } catch (\Exception $e) {
            Craft::$app->getErrorHandler()->logException($e);
            $this->stderr('Error: ' . $e->getMessage() . PHP_EOL, Console::FG_RED);
            return ExitCode::UNSPECIFIED_ERROR;
        }
        return ExitCode::OK;
    }
}