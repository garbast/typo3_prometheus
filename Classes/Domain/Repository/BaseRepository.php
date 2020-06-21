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

    public function getTcaFieldLabel(string $table, string $field, $value): string
    {
        if (
            !isset($GLOBALS['TCA'][$table])
            || !isset($GLOBALS['TCA'][$table]['columns'][$field])
        ) {
            return $value;
        }

        $label = $value;
        foreach ($GLOBALS['TCA'][$table]['columns'][$field]['config']['items'] as $item) {
            if ($item[1] == $value) {
                $label = $this->getLanguageService()->sL($item[0]);
                break;
            }
        }

        return $label ?? $value;
    }

    public function getTcaTableLabel(string $table): string
    {
        $label = $table;
        if (isset($GLOBALS['TCA'][$table])) {
            $label = $this->getLanguageService()->sL($GLOBALS['TCA'][$table]['ctrl']['title']);
        }

        return $label ?? $table;
    }

    protected function getQueryBuilderForTable(string $table = ''): \TYPO3\CMS\Core\Database\Query\QueryBuilder
    {
        /** @var \TYPO3\CMS\Core\Database\ConnectionPool $connectionPool */
        $connectionPool = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
            \TYPO3\CMS\Core\Database\ConnectionPool::class
        );
        return $connectionPool->getQueryBuilderForTable($table !== '' ? $table : $this->tableName);
    }

    protected function getLanguageService(): \TYPO3\CMS\Core\Localization\LanguageService
    {
        return $GLOBALS['LANG'];
    }
}
