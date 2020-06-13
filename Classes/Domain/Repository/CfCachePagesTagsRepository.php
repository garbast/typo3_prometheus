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
    public function getMetricsValues()
    {
        $data = [];

        $cachedPagesTags = $this->getDatabaseConnection()->exec_SELECTcountRows(
            'id',
            'cf_cache_pages_tags',
            '1=1' . $this->getEnableFields('cf_cache_pages_tags')
        );

        if ($cachedPagesTags !== false) {
            $data['typo3_cf_cache_pages_tags_total'] = $cachedPagesTags;
        }

        $cachedPagesTags = $this->getDatabaseConnection()->exec_SELECTcountRows(
            'distinct(tag)',
            'cf_cache_pages_tags',
            '1=1' . $this->getEnableFields('cf_cache_pages_tags')
        );

        if ($cachedPagesTags !== false) {
            $data['typo3_cf_cache_pages_tags_distinct_total'] = $cachedPagesTags;
        }

        return $data;
    }
}
