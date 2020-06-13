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
    protected $tablename = 'prometheus_metrics';

    /**
     * @return array
     */
    public function getAllMetrics()
    {
        return $this->getDatabaseConnection()->exec_SELECTgetRows(
            'concat(metric_key, \' \', metric_value) as row',
            $this->tablename,
            '',
            '',
            '',
            '',
            'row'
        );
    }

    /**
     * @param array $data
     *
     * @return void
     */
    public function saveDataToDb($data)
    {
        $this->getDatabaseConnection()->exec_INSERTmultipleRows(
            $this->tablename,
            ['metric_key', 'metric_value', 'tstamp'],
            $data
        );
    }

    public function deleteOldMetricData($keys)
    {
        $this->getDatabaseConnection()->exec_DELETEquery(
            $this->tablename,
            'metric_key in (' . implode(
                ',',
                $this->getDatabaseConnection()->fullQuoteArray($keys, $this->tablename)
            ) . ')'
        );
    }
}
