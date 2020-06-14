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

class CfCachePagesTagsRepository extends BaseRepository
{
    protected $tableName = 'cf_cache_pages_tags';

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
            $data['typo3_cf_cache_pages_tags_total'] = $cachedPagesTags;
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
            $data['typo3_cf_cache_pages_tags_distinct_total'] = $distinctCachedPagesTags;
        }

        return $data;
    }
}
