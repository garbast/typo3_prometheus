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

class TtContentRepository extends BaseRepository
{
    protected $tableName = 'tt_content';

    public function getMetricsValues(): array
    {
        $data = [];

        $queryBuilder = $this->getQueryBuilderForTable();
        $contentTypesByLanguage = $queryBuilder
            ->selectLiteral('COUNT(uid) AS count')
            ->select('sys_language_uid', 'CType', 'list_type')
            ->from($this->tableName)
            ->where($queryBuilder->expr()->gte('sys_language_uid', 0))
            ->groupBy('sys_language_uid', 'CType', 'list_type')
            ->orderBy('sys_language_uid', 'ASC')
            ->execute()
            ->fetchAll(\Doctrine\DBAL\FetchMode::ASSOCIATIVE);

        foreach ($contentTypesByLanguage as $singleContentTypes) {
            $key = 'typo3_tt_content_total{sys_language_uid="' . $singleContentTypes['sys_language_uid']
                . '", cType="' . $singleContentTypes['cType'] . '"}';
            $data[$key] = $singleContentTypes['count'];
        }

        return $data;
    }
}
