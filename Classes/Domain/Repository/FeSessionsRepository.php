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

class FeSessionsRepository extends BaseRepository
{
    public function getMetricsValues()
    {
        $data = [];

        $cachedPages = $this->getDatabaseConnection()->exec_SELECTcountRows(
            'ses_id',
            'fe_sessions',
            '1=1' . $this->getEnableFields('fe_sessions')
        );

        if ($cachedPages !== false) {
            $data['typo3_fe_sessions_total'] = $cachedPages;
        }

        return $data;
    }
}
