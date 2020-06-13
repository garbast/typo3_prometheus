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
    public function getMetricsValues()
    {
        $data = [];

        $contentTypesByLanguage = $this->getDatabaseConnection()->exec_SELECTgetRows(
            'count(uid) as count, sys_language_uid, cType',
            'tt_content',
            'sys_language_uid >=0' . $this->getEnableFields('tt_content'),
            'sys_language_uid, cType',
            'sys_language_uid asc'
        );

        foreach ($contentTypesByLanguage as $singleContentTypes) {
            $key = 'typo3_tt_content_total{sys_language_uid="' . $singleContentTypes['sys_language_uid']
                . '", cType="' . $singleContentTypes['cType'] . '"}';
            $data[$key] = $singleContentTypes['count'];
        }

        return $data;
    }
}
