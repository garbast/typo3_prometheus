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

class TtContentRepository extends BaseRepository
{
    protected $tableName = 'tt_content';

    public function getMetricsValues(): array
    {
        $data = [];

        $queryBuilder = $this->getQueryBuilderForTable();
        $contentTypesByLanguage = $queryBuilder
            ->select('sys_language_uid', 'CType', 'list_type')
            ->addSelectLiteral('COUNT(uid) AS count')
            ->from($this->tableName)
            ->where($queryBuilder->expr()->gte('sys_language_uid', 0))
            ->groupBy('sys_language_uid', 'CType', 'list_type')
            ->orderBy('sys_language_uid', 'ASC')
            ->addOrderBy('list_type', 'ASC')
            ->execute()
            ->fetchAll(\Doctrine\DBAL\FetchMode::ASSOCIATIVE);

        $contentSum = 0;
        foreach ($contentTypesByLanguage as $contentTypeByLanguage) {
            $key = 'typo3_tt_content_total{sys_language_uid="' . $contentTypeByLanguage['sys_language_uid'] . '"';
            if ($contentTypeByLanguage['CType'] !== 'list') {
                $key .= ', CType="' . $contentTypeByLanguage['CType'] . '"';
            }
            if ($contentTypeByLanguage['list_type'] !== '') {
                $key .= ', list_type="' . $contentTypeByLanguage['list_type'] . '"';
            }
            $key .= '}';
            $data[$key] = $contentTypeByLanguage['count'];
            $contentSum += $contentTypeByLanguage['count'];
        }

        $data['typo3_tt_content_total'] = $contentSum;

        return $data;
    }
}
