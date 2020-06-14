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

class PowermailRepository extends BaseRepository
{
    protected $tableName = 'tx_powermail_domain_model_form';

    public function getMetricsValues(): array
    {
        $data = [];

        $queryBuilder = $this->getQueryBuilderForTable();
        $forms = $queryBuilder
            ->select('f.uid', 'f.title')
            ->selectLiteral('COUNT(m.uid) AS mail_count')
            ->from($this->tableName, 'f')
            ->innerJoin(
                'f',
                'tx_powermail_domain_model_mail',
                'm',
                $queryBuilder->expr()->eq('f.uid', 'm.form')
            )
            ->where($queryBuilder->expr()->eq('sys_language_uid', 0))
            ->groupBy('f.uid')
            ->execute()
            ->fetchAll(\Doctrine\DBAL\FetchMode::ASSOCIATIVE);

        if ($forms !== null) {
            $data['typo3_powermail_forms_total'] = count($forms);
        }

        $mailSum = 0;
        foreach ($forms as $formData) {
            $dataKey = 'typo3_powermail_form_mails_total{form_title="'
                . addcslashes($formData['title'], '"\\')
                . '", form_uid="' . $formData['uid'] . '"}';
            $data[$dataKey] = $formData['mail_count'];
            $mailSum += $formData['mail_count'];
        }

        $data['typo3_powermail_mails_total'] = $mailSum;

        return $data;
    }
}
