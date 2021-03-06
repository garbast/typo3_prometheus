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
 */

namespace Mfc\Prometheus\Domain\Repository;

use TYPO3\CMS\Core\Utility\GeneralUtility;

class SysLockedRecordsRepository extends BaseRepository
{
    protected $tableName = 'sys_lockedrecords';

    public function getMetricsValues(): array
    {
        $data = [];

        $queryBuilder = $this->getQueryBuilderForTable();
        $lockedRecords = $queryBuilder
            ->select('record_table')
            ->addSelectLiteral('COUNT(uid) AS count')
            ->from($this->tableName)
            ->groupBy('record_table')
            ->orderBy('record_table', 'ASC')
            ->execute()
            ->fetchAll(\Doctrine\DBAL\FetchMode::ASSOCIATIVE);

        foreach ($lockedRecords as $singleContentTypes) {
            $key = 'typo3_sys_locked_records_total{record_table="'
                . $this->getTcaTableLabel($singleContentTypes['record_table']) . '"}';
            $data[$key] = $singleContentTypes['count'];
        }

        if (empty($lockedRecords)) {
            /** @var MetricsRepository $metricsRepository */
            $metricsRepository = GeneralUtility::makeInstance(MetricsRepository::class);
            $metricsRepository->deleteLikeMetricKey('typo3_sys_locked_records_total');

            $data['typo3_sys_locked_records_total'] = 0;
        }

        return $data;
    }
}
