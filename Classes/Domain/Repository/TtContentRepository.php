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

use TYPO3\CMS\Core\Utility\GeneralUtility;

class TtContentRepository extends BaseRepository
{
    protected $tableName = 'tt_content';

    public function getMetricsValues(): array
    {
        $data = [];

        $data = $this->getContentTypes($data);
        $data = $this->getPluginTypes($data);
        $data = $this->getLanguages($data);

        return $data;
    }

    protected function getContentTypes(array $data): array
    {
        $queryBuilder = $this->getQueryBuilderForTable();
        $contentTypesByLanguage = $queryBuilder
            ->select('sys_language_uid', 'CType')
            ->addSelectLiteral('COUNT(uid) AS count')
            ->from($this->tableName)
            ->where(
                $queryBuilder->expr()->gte('sys_language_uid', 0),
                $queryBuilder->expr()->neq('CType', $queryBuilder->createNamedParameter('list'))
            )
            ->groupBy('sys_language_uid', 'CType')
            ->orderBy('sys_language_uid', 'ASC')
            ->addOrderBy('list_type', 'ASC')
            ->execute()
            ->fetchAll(\Doctrine\DBAL\FetchMode::ASSOCIATIVE);

        $contentSum = 0;
        foreach ($contentTypesByLanguage as $contentTypeByLanguage) {
            $label = $this->getTcaFieldLabel('tt_content', 'CType', $contentTypeByLanguage['CType']);
            $key = 'typo3_tt_content_ctypes';
            $key .= '{sys_language_uid="' . $contentTypeByLanguage['sys_language_uid'] . '"';
            $key .= ',CType="' . $label . '"';
            $key .= '}';
            $data[$key] = $contentTypeByLanguage['count'];
            $contentSum += $contentTypeByLanguage['count'];
        }

        $data['typo3_tt_content_ctypes'] = $contentSum;

        /** @var MetricsRepository $metricsRepository */
        $metricsRepository = GeneralUtility::makeInstance(MetricsRepository::class);
        $metricsRepository->deleteLikeMetricKey('typo3_tt_content_ctypes');

        return $data;
    }

    protected function getPluginTypes(array $data): array
    {
        $queryBuilder = $this->getQueryBuilderForTable();
        $contentTypesByLanguage = $queryBuilder
            ->select('sys_language_uid', 'list_type')
            ->addSelectLiteral('COUNT(uid) AS count')
            ->from($this->tableName)
            ->where(
                $queryBuilder->expr()->gte('sys_language_uid', 0),
                $queryBuilder->expr()->eq('CType', $queryBuilder->createNamedParameter('list'))
            )
            ->groupBy('sys_language_uid', 'list_type')
            ->orderBy('sys_language_uid', 'ASC')
            ->addOrderBy('list_type', 'ASC')
            ->execute()
            ->fetchAll(\Doctrine\DBAL\FetchMode::ASSOCIATIVE);

        $contentSum = 0;
        foreach ($contentTypesByLanguage as $contentTypeByLanguage) {
            $label = $this->getTcaFieldLabel('tt_content', 'list_type', $contentTypeByLanguage['list_type']);
            $key = 'typo3_tt_content_plugins';
            $key .= '{sys_language_uid="' . $contentTypeByLanguage['sys_language_uid'] . '"';
            $key .= ',list_type="' . $label . '"';
            $key .= '}';
            $data[$key] = $contentTypeByLanguage['count'];
            $contentSum += $contentTypeByLanguage['count'];
        }

        $data['typo3_tt_content_plugins'] = $contentSum;

        /** @var MetricsRepository $metricsRepository */
        $metricsRepository = GeneralUtility::makeInstance(MetricsRepository::class);
        $metricsRepository->deleteLikeMetricKey('typo3_tt_content_plugins');

        return $data;
    }

    protected function getLanguages(array $data): array
    {
        $queryBuilder = $this->getQueryBuilderForTable();
        $contentTypesByLanguage = $queryBuilder
            ->select('sys_language_uid')
            ->addSelectLiteral('COUNT(uid) AS count')
            ->from($this->tableName)
            ->where(
                $queryBuilder->expr()->gte('sys_language_uid', 0)
            )
            ->groupBy('sys_language_uid')
            ->orderBy('sys_language_uid', 'ASC')
            ->execute()
            ->fetchAll(\Doctrine\DBAL\FetchMode::ASSOCIATIVE);

        $contentSum = 0;
        foreach ($contentTypesByLanguage as $contentTypeByLanguage) {
            $key = 'typo3_tt_content_languages';
            $key .= '{sys_language_uid="' . $contentTypeByLanguage['sys_language_uid'] . '"}';
            $data[$key] = $contentTypeByLanguage['count'];
            $contentSum += $contentTypeByLanguage['count'];
        }

        $data['typo3_tt_content'] = $contentSum;

        /** @var MetricsRepository $metricsRepository */
        $metricsRepository = GeneralUtility::makeInstance(MetricsRepository::class);
        $metricsRepository->deleteLikeMetricKey('typo3_tt_content_languages');

        return $data;
    }
}
