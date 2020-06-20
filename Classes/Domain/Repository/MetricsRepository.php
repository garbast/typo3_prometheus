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

class MetricsRepository extends BaseRepository
{
    protected $tableName = 'tx_prometheus_metrics';

    public function getAllMetrics(): array
    {
        $queryBuilder = $this->getQueryBuilderForTable();
        $result = $queryBuilder
            ->selectLiteral('CONCAT(metric_key, \' \', metric_value) AS row')
            ->from($this->tableName)
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

    public function deleteOldMetricData(array $metricKeys)
    {
        $queryBuilder = $this->getQueryBuilderForTable();
        $queryBuilder
            ->delete($this->tableName)
            ->where(
                $queryBuilder->expr()->in(
                    'metric_key',
                    $queryBuilder->createNamedParameter($metricKeys, \Doctrine\DBAL\Connection::PARAM_STR_ARRAY)
                )
            )
            ->execute();
    }

    public function deleteLikeMetricKey(string $metricKey)
    {
        $queryBuilder = $this->getQueryBuilderForTable();
        $queryBuilder
            ->delete($this->tableName)
            ->where(
                $queryBuilder->expr()->like(
                    'metric_key',
                    $queryBuilder->createNamedParameter(
                        $queryBuilder->escapeLikeWildcards($metricKey),
                        \PDO::PARAM_STR
                    )
                )
            )
            ->execute();
    }
}
