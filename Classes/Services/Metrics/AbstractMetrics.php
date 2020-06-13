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

namespace Mfc\Prometheus\Services\Metrics;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;

abstract class AbstractMetrics implements MetricsInterface
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var string
     */
    protected $velocity = MetricsInterface::SLOW;

    /**
     * AbstractMetrics constructor
     */
    public function __construct()
    {
        $this->objectManager = GeneralUtility::makeInstance(ObjectManager::class);
    }

    /**
     * @return string
     */
    public function getVelocity()
    {
        return $this->velocity;
    }

    /**
     * @return array
     */
    public function getMetricsValues()
    {
        return [];
    }

    /**
     * @param array $data
     *
     * @return array
     */
    protected function prepareDataToInsert($data)
    {
        $output = [];

        foreach ($data as $dataKey => $dataValue) {
            $output[$dataKey] = [$dataKey, $dataValue, $GLOBALS['EXEC_TIME']];
        }

        return $output;
    }
}
