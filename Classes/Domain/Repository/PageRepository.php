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

class PageRepository extends BaseRepository
{
    protected $tableName = 'pages';

    public function getMetricsValues(): array
    {
        $data = [];

        $data = $this->getDefaultPages($data);
        $data = $this->getPageLanguages($data);

        return $data;
    }

    protected function getDefaultPages(array $data): array
    {
        $queryBuilder = $this->getQueryBuilderForTable();
        $defaultPages = $queryBuilder
            ->select('doktype')
            ->addSelectLiteral('COUNT(uid) AS count')
            ->from($this->tableName)
            ->groupBy('doktype')
            ->orderBy('doktype', 'ASC')
            ->execute()
            ->fetchAll(\Doctrine\DBAL\FetchMode::ASSOCIATIVE);

        $pageSum = 0;
        foreach ($defaultPages as $defaultPage) {
            $label = $this->getTcaFieldLabel('pages', 'doktype', $defaultPage['doktype']);
            $data['typo3_pages_types{doktype="' . $label . '"}'] = $defaultPage['count'];
            $pageSum += $defaultPage['count'];
        }

        if ($pageSum) {
            $data['typo3_pages_types'] = $pageSum;
        }

        return $data;
    }

    protected function getPageLanguages(array $data): array
    {
        $queryBuilder = $this->getQueryBuilderForTable();
        $pageTranslations = $queryBuilder
            ->select('sys_language_uid')
            ->addSelectLiteral('COUNT(uid) AS count')
            ->from($this->tableName)
            ->groupBy('sys_language_uid')
            ->orderBy('sys_language_uid', 'ASC')
            ->execute()
            ->fetchAll(\Doctrine\DBAL\FetchMode::ASSOCIATIVE);

        $pageSum = 0;
        foreach ($pageTranslations as $pageTranslation) {
            $data['typo3_pages_languages{sys_language_uid="' . $pageTranslation['sys_language_uid'] . '"}'] =
                $pageTranslation['count'];
            $pageSum += $pageTranslation['count'];
        }

        if ($pageSum) {
            $data['typo3_pages_languages'] = $pageSum;
        }

        return $data;
    }
}
