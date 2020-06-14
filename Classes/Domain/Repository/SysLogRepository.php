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

class SysLogRepository extends BaseRepository
{
    protected $tableName = 'sys_log';

    public function getMetricsValues(): array
    {
        $data = [];

        $data = $this->getSuccessfulLogins($data);
        $data = $this->getFailedLogins($data);
        $data = $this->getCaptchaFailedLogins($data);
        $data = $this->getClearCaches($data);
        $data = $this->getInsertedRecords($data);
        $data = $this->getDeletedRecords($data);
        $data = $this->getUpdatedRecords($data);

        return $data;
    }

    protected function getSuccessfulLogins(array $data): array
    {
        $queryBuilder = $this->getQueryBuilderForTable();
        $successfulLogins = $queryBuilder
            ->count('uid')
            ->from($this->tableName)
            ->where(
                $queryBuilder->expr()->eq('type', 255),
                $queryBuilder->expr()->eq('error', 0)
            )
            ->execute()
            ->fetchColumn(0);

        if ($successfulLogins !== false) {
            $data['typo3_sys_log_successfull_logins_total'] = $successfulLogins;
        }

        return $data;
    }

    protected function getFailedLogins(array $data): array
    {
        $queryBuilder = $this->getQueryBuilderForTable();
        $failedLogins = $queryBuilder
            ->count('uid')
            ->from($this->tableName)
            ->where(
                $queryBuilder->expr()->eq('type', 255),
                $queryBuilder->expr()->eq('error', 3)
            )
            ->execute()
            ->fetchColumn(0);

        if ($failedLogins !== false) {
            $data['typo3_sys_log_failed_logins_total'] = $failedLogins;
        }

        return $data;
    }

    protected function getCaptchaFailedLogins(array $data): array
    {
        $queryBuilder = $this->getQueryBuilderForTable();
        $captchaFailedLogins = $queryBuilder
            ->count('uid')
            ->from($this->tableName)
            ->where(
                $queryBuilder->expr()->eq('type', 255),
                $queryBuilder->expr()->eq('error', 3),
                $queryBuilder->expr()->eq('details_nr', 3)
            )
            ->execute()
            ->fetchColumn(0);

        if ($captchaFailedLogins !== false) {
            $data['typo3_sys_log_captcha_failed_logins_total'] = $captchaFailedLogins;
        }

        return $data;
    }

    protected function getClearCaches(array $data): array
    {
        $queryBuilder = $this->getQueryBuilderForTable();
        $clearCaches = $queryBuilder
            ->count('uid')
            ->from($this->tableName)
            ->where(
                $queryBuilder->expr()->eq('type', 3)
            )
            ->execute()
            ->fetchColumn(0);

        if ($clearCaches !== false) {
            $data['typo3_sys_log_cleared_caches_total'] = $clearCaches;
        }

        return $data;
    }

    protected function getInsertedRecords(array $data): array
    {
        $queryBuilder = $this->getQueryBuilderForTable();
        $insertedRecords = $queryBuilder
            ->select('tablename')
            ->selectLiteral('COUNT(uid) AS count')
            ->from($this->tableName)
            ->where(
                $queryBuilder->expr()->eq('type', 1),
                $queryBuilder->expr()->eq('action', 1)
            )
            ->groupBy('tablename')
            ->orderBy('tablename', 'ASC')
            ->execute()
            ->fetchAll(\Doctrine\DBAL\FetchMode::ASSOCIATIVE);

        foreach ($insertedRecords as $insertedRecord) {
            $key = 'typo3_sys_log_inserted_records_total{tablename="' . $insertedRecord['tablename'] . '"}';
            $data[$key] = $insertedRecord['count'];
        }

        return $data;
    }

    protected function getDeletedRecords(array $data): array
    {
        $queryBuilder = $this->getQueryBuilderForTable();
        $deletedRecords = $queryBuilder
            ->select('tablename')
            ->selectLiteral('COUNT(uid) AS count')
            ->from($this->tableName)
            ->where(
                $queryBuilder->expr()->eq('type', 1),
                $queryBuilder->expr()->eq('action', 3)
            )
            ->groupBy('tablename')
            ->orderBy('tablename', 'ASC')
            ->execute()
            ->fetchAll(\Doctrine\DBAL\FetchMode::ASSOCIATIVE);

        foreach ($deletedRecords as $deletedRecord) {
            $key = 'typo3_sys_log_deleted_records_total{tablename="' . $deletedRecord['tablename'] . '"}';
            $data[$key] = $deletedRecord['count'];
        }

        return $data;
    }

    protected function getUpdatedRecords(array $data): array
    {
        $queryBuilder = $this->getQueryBuilderForTable();
        $updatedRecords = $queryBuilder
            ->select('tablename')
            ->selectLiteral('COUNT(uid) AS count')
            ->from($this->tableName)
            ->where(
                $queryBuilder->expr()->eq('type', 1),
                $queryBuilder->expr()->eq('action', 2)
            )
            ->groupBy('tablename')
            ->orderBy('tablename', 'ASC')
            ->execute()
            ->fetchAll(\Doctrine\DBAL\FetchMode::ASSOCIATIVE);

        foreach ($updatedRecords as $singleContentTypes) {
            $key = 'typo3_sys_log_updated_records_total{tablename="' . $singleContentTypes['tablename'] . '"}';
            $data[$key] = $singleContentTypes['count'];
        }

        return $data;
    }
}
