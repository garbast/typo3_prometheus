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

class CachePagesRepository extends BaseRepository
{
    protected $tableName = 'cf_cache_pages';

    /**
     * CachePagesRepository constructor.
     *
     * @todo Remove once 9.5 support get removed
     */
    public function __construct()
    {
        if (\TYPO3\CMS\Core\Utility\VersionNumberUtility::convertVersionNumberToInteger(TYPO3_branch) > 10000000) {
            $this->tableName = 'cache_pages';
        }
    }

    public function getMetricsValues(): array
    {
        $data = [];

        $queryBuilder = $this->getQueryBuilderForTable();
        $cachedPages = $queryBuilder
            ->count('id')
            ->from($this->tableName)
            ->execute()
            ->fetchColumn(0);

        if ($cachedPages !== false) {
            $data['typo3_cache_pages_total'] = $cachedPages;
        } else {
            /** @var MetricsRepository $metricsRepository */
            $metricsRepository = GeneralUtility::makeInstance(MetricsRepository::class);
            $metricsRepository->deleteLikeMetricKey('typo3_cache_pages_total');
        }

        return $data;
    }
}
