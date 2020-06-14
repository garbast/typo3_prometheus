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

class BeSessionsRepository extends BaseRepository
{
    protected $tableName = 'be_sessions';

    public function getMetricsValues(): array
    {
        $data = [];

        $queryBuilder = $this->getQueryBuilderForTable();
        $backendSessions = $queryBuilder
            ->count('ses_id')
            ->from($this->tableName)
            ->execute()
            ->fetchColumn(0);

        if ($backendSessions !== false) {
            $data['typo3_be_sessions_total'] = $backendSessions;
        }

        return $data;
    }
}
