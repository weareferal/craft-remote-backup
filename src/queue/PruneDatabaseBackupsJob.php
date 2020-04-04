<?php

namespace weareferal\remotebackup\queue;

use craft\queue\BaseJob;

use weareferal\remotebackup\RemoteBackup;

class PruneDatabaseBackupsJob extends BaseJob
{
    public function execute($queue)
    {
        RemoteBackup::getInstance()->remotebackup->pruneDatabaseBackups();
    }

    protected function defaultDescription()
    {
        return 'Prune remote database backups';
    }
}