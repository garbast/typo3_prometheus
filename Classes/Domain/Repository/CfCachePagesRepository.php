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

class CfCachePagesRepository extends BaseRepository
{
    protected $tableName = 'cf_cache_pages';

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
            $data['typo3_cf_cache_pages_total'] = $cachedPages;
        }

        return $data;
    }
}
