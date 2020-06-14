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

class FeUsersRepository extends BaseRepository
{
    protected $tableName = 'fe_users';

    public function getMetricsValues(): array
    {
        $data = [];

        $queryBuilder = $this->getQueryBuilderForTable();
        $frontendUsers = $queryBuilder
            ->count('uid')
            ->from($this->tableName)
            ->execute()
            ->fetchColumn(0);

        if ($frontendUsers !== false) {
            $data['typo3_fe_users_total'] = $frontendUsers;
        }

        return $data;
    }
}
