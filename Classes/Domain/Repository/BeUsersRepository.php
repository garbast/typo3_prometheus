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

class BeUsersRepository extends BaseRepository
{
    protected $tableName = 'be_users';

    public function getMetricsValues(): array
    {
        $data = [];

        $queryBuilder = $this->getQueryBuilderForTable();
        $backendUsers = $queryBuilder
            ->count('uid')
            ->from($this->tableName)
            ->execute()
            ->fetchColumn(0);

        if ($backendUsers !== false) {
            $data['typo3_be_users_total'] = $backendUsers;
        }

        return $data;
    }
}
