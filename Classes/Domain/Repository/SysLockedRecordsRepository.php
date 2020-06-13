<?php

/*
 * This file is part of the Mfc\Prometheus project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

namespace Mfc\Prometheus\Domain\Repository;

class SysLockedRecordsRepository extends BaseRepository
{
    public function getMetricsValues()
    {
        $data = [];

        $lockedRecords = $this->getDatabaseConnection()->exec_SELECTgetRows(
            'count(uid) as count,record_table',
            'sys_lockedrecords',
            '1=1' . $this->getEnableFields('sys_lockedrecords'),
            'record_table',
            'record_table asc'
        );

        foreach ($lockedRecords as $singleContentTypes) {
            $key = 'typo3_sys_locked_records_total{record_table="' . $singleContentTypes['record_table'] . '"}';
            $data[$key] = $singleContentTypes['count'];
        }

        return $data;
    }
}
