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

class CachePagesTagsRepository extends BaseRepository
{
    protected $tableName = 'cf_cache_pages_tags';

    /**
     * CachePagesTagsRepository constructor.
     *
     * @todo Remove once 9.5 support get removed
     */
    public function __construct()
    {
        if (\TYPO3\CMS\Core\Utility\VersionNumberUtility::convertVersionNumberToInteger(TYPO3_branch) > 10000000) {
            $this->tableName = 'cache_pages_tags';
        }
    }

    public function getMetricsValues(): array
    {
        $data = [];

        $data = $this->getCachePagesTagsTotal($data);
        $data = $this->getCachePagesTagsDistinct($data);

        return $data;
    }

    protected function getCachePagesTagsTotal(array $data): array
    {
        $queryBuilder = $this->getQueryBuilderForTable();
        $cachedPagesTags = $queryBuilder
            ->count('id')
            ->from($this->tableName)
            ->execute()
            ->fetchColumn(0);

        if ($cachedPagesTags !== false) {
            $data['typo3_cache_pages_tags_total'] = $cachedPagesTags;
        } else {
            /** @var MetricsRepository $metricsRepository */
            $metricsRepository = GeneralUtility::makeInstance(MetricsRepository::class);
            $metricsRepository->deleteLikeMetricKey('typo3_cache_pages_tags_total');
        }

        return $data;
    }

    protected function getCachePagesTagsDistinct(array $data): array
    {
        $queryBuilder = $this->getQueryBuilderForTable();
        $distinctCachedPagesTags = $queryBuilder
            ->selectLiteral('COUNT(DISTINCT tag)')
            ->from($this->tableName)
            ->execute()
            ->fetchColumn(0);

        if ($distinctCachedPagesTags !== false) {
            $data['typo3_cache_pages_tags_distinct_total'] = $distinctCachedPagesTags;
        } else {
            /** @var MetricsRepository $metricsRepository */
            $metricsRepository = GeneralUtility::makeInstance(MetricsRepository::class);
            $metricsRepository->deleteLikeMetricKey('typo3_cache_pages_tags_distinct_total');
        }

        return $data;
    }
}
