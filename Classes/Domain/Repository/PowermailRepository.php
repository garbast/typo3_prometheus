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

class PowermailRepository extends BaseRepository
{
    /**
     * @return array
     */
    public function getMetricsValues()
    {
        $data = [];

        $forms = $this->getDatabaseConnection()->exec_SELECTgetRows(
            'tx_powermail_domain_model_form.uid, tx_powermail_domain_model_form.title, count(mail.uid) AS mail_count',
            'tx_powermail_domain_model_form, tx_powermail_domain_model_mail AS mail',
            'tx_powermail_domain_model_form.uid = mail.form' . $this->getEnableFields('tx_powermail_domain_model_form'),
            'tx_powermail_domain_model_form.uid',
            '',
            '',
            'uid'
        );

        if ($forms !== null) {
            $data['typo3_powermail_forms_total'] = count($forms);
        }


        $mailSum = 0;
        foreach ($forms as $formUid => $formData) {
            $data['typo3_powermail_form_mails_total{form_title="' . addcslashes(
                $formData['title'],
                '"\\'
            ) . '", form_uid="' . $formUid . '"}'] =
                $formData['mail_count'];
            $mailSum += $formData['mail_count'];
        }

        $data['typo3_powermail_mails_total'] = $mailSum;

        return $data;
    }
}
