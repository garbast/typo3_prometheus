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

class MetricsRepository extends BaseRepository
{
    protected $tableName = 'prometheus_metrics';

    public function getAllMetrics(): array
    {
        $queryBuilder = $this->getQueryBuilderForTable();
        $result = $queryBuilder
            ->selectLiteral('CONCAT(metric_key, \' \', metric_value) AS row')
            ->from($this->tableName)
            ->where($queryBuilder->expr()->eq('sys_language_uid', 0))
            ->execute();

        $rows = [];
        while ($row = $result->fetch()) {
            $rows[$row['row']] = $row;
        }

        return $rows;
    }

    public function insertMetrics(array $metrics)
    {
        $queryBuilder = $this->getQueryBuilderForTable();

        foreach ($metrics as $metric) {
            $queryBuilder
                ->insert($this->tableName)
                ->values($metric)
                ->execute();
        }
    }

    public function deleteOldMetricData(array $keys)
    {
        $queryBuilder = $this->getQueryBuilderForTable();
        $queryBuilder
            ->delete($this->tableName)
            ->where(
                $queryBuilder->expr()->in(
                    'metric_key',
                    $queryBuilder->createNamedParameter($keys, \Doctrine\DBAL\Connection::PARAM_STR_ARRAY)
                )
            )
            ->execute();
    }
}
