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
    protected $repositoryClassName = '';

    /**
     * @var string
     */
    protected $velocity = MetricsInterface::SLOW;

    /**
     * AbstractMetrics constructor
     */
    public function __construct()
    {
        $this->objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(ObjectManager::class);
    }

    public function getVelocity(): string
    {
        return $this->velocity;
    }

    public function getMetricsValues(): array
    {
        return $this->prepareDataToInsert($this->getRepository()->getMetricsValues());
    }

    protected function prepareDataToInsert(array $data): array
    {
        $output = [];

        foreach ($data as $dataKey => $dataValue) {
            $output[$dataKey] = [
                'metric_key' => $dataKey,
                'metric_value' => $dataValue,
                'tstamp' => $GLOBALS['EXEC_TIME']
            ];
        }

        return $output;
    }

    protected function getRepository(): \Mfc\Prometheus\Domain\Repository\BaseRepository
    {
        /** @var \Mfc\Prometheus\Domain\Repository\BaseRepository $repository */
        $repository = $this->objectManager->get($this->repositoryClassName);
        return $repository;
    }
}
