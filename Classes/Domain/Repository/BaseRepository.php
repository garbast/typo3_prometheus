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

abstract class BaseRepository
{
    /**
     * @var string
     */
    protected $tableName = '';

    public function getMetricsValues(): array
    {
        return [];
    }

    protected function getQueryBuilderForTable(string $table = ''): \TYPO3\CMS\Core\Database\Query\QueryBuilder
    {
        /** @var \TYPO3\CMS\Core\Database\ConnectionPool $connectionPool */
        $connectionPool = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
            \TYPO3\CMS\Core\Database\ConnectionPool::class
        );
        return $connectionPool->getQueryBuilderForTable($table !== '' ? $table : $this->tableName);
    }
}
