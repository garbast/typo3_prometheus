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

class FeSessionsRepository extends BaseRepository
{
    protected $tableName = 'fe_sessions';

    public function getMetricsValues(): array
    {
        $data = [];

        $queryBuilder = $this->getQueryBuilderForTable();
        $frontendSessions = $queryBuilder
            ->count('ses_id')
            ->from($this->tableName)
            ->execute()
            ->fetchColumn(0);

        if ($frontendSessions !== false) {
            $data['typo3_fe_sessions_total'] = $frontendSessions;
        } else {
            /** @var MetricsRepository $metricsRepository */
            $metricsRepository = GeneralUtility::makeInstance(MetricsRepository::class);
            $metricsRepository->deleteLikeMetricKey('typo3_fe_sessions_total');
        }

        return $data;
    }
}
