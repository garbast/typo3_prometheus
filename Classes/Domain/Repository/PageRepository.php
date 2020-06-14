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

class PageRepository extends BaseRepository
{
    protected $tableName = 'pages';

    public function getMetricsValues(): array
    {
        $data = [];

        $data = $this->getDefaultPages($data);
        $data = $this->getPageTranslations($data);

        return $data;
    }

    protected function getDefaultPages(array $data): array
    {
        $queryBuilder = $this->getQueryBuilderForTable();
        $defaultPages = $queryBuilder
            ->count('uid')
            ->from($this->tableName)
            ->where($queryBuilder->expr()->eq('sys_language_uid', 0))
            ->execute()
            ->fetchColumn(0);

        if ($defaultPages !== false) {
            $data['typo3_pages_total{sys_language_uid="0"}'] = $defaultPages;
        }

        return $data;
    }

    protected function getPageTranslations(array $data): array
    {
        $queryBuilder = $this->getQueryBuilderForTable();
        $pageTranslations = $queryBuilder
            ->selectLiteral('COUNT(uid) AS count')
            ->select('sys_language_uid')
            ->from($this->tableName)
            ->where($queryBuilder->expr()->gt('sys_language_uid', 0))
            ->groupBy('sys_language_uid')
            ->orderBy('sys_language_uid', 'ASC')
            ->execute()
            ->fetchAll(\Doctrine\DBAL\FetchMode::ASSOCIATIVE);

        foreach ($pageTranslations as $pageTranslation) {
            $data['typo3_pages_total{sys_language_uid="' . $pageTranslation['sys_language_uid'] . '"}'] =
                $pageTranslation['count'];
        }

        return $data;
    }
}
