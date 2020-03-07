<?php

/**
 * test plugin for Craft CMS 3.x
 *
 * test
 *
 * @link      test.com
 * @copyright Copyright (c) 2019 test
 */

namespace weareferal\backup\console\controllers;

use weareferal\backup\Test;

use Craft;
use yii\console\Controller;
use yii\helpers\Console;
use yii\console\ExitCode;

use weareferal\backup\Backup;

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
            $path = Backup::getInstance()->backup->createDatabaseBackup();
            $this->stdout("Created local database backup: " . $path . PHP_EOL, Console::FG_GREEN);
        } catch (\Exception $e) {
            Craft::$app->getErrorHandler()->logException($e);
            $this->stderr('Error: ' . $e->getMessage() . PHP_EOL, Console::FG_RED);
            return ExitCode::UNSPECIFIED_ERROR;
        }
        return ExitCode::OK;
    }
}
