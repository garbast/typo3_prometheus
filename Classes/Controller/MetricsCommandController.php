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

namespace Mfc\Prometheus\Controller;

use Mfc\Prometheus\Domain\Repository\MetricsRepository;
use Mfc\Prometheus\Services\Metrics\MetricsInterface;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class MetricsCommandController extends \TYPO3\CMS\Extbase\Mvc\Controller\CommandController
{
    /**
     * @var array
     */
    protected $metricsToWork = [];

    /**
     * @var MetricsRepository
     */
    protected $metricsRepository;

    public function __construct()
    {
        $this->metricsRepository = GeneralUtility::makeInstance(MetricsRepository::class);
    }

    public function generateAllFastMetricsCommand()
    {
        $this->initializeMetrics(MetricsInterface::FAST);
        $this->getValuesAndWriteToDb();
    }

    public function generateAllSlowMetricsCommand()
    {
        $this->initializeMetrics(MetricsInterface::SLOW);
        $this->getValuesAndWriteToDb();
    }

    public function generateAllMediumMetricsCommand()
    {
        $this->initializeMetrics(MetricsInterface::MEDIUM);
        $this->getValuesAndWriteToDb();
    }

    protected function initializeMetrics($velocity = '')
    {
        /** @var ExtensionConfiguration $extensionConfiguration */
        $extensionConfiguration = GeneralUtility::makeInstance(ExtensionConfiguration::class);
        $metricsToMeasure = $extensionConfiguration->get('prometheus', 'metricsToMeasure');

        foreach ($metricsToMeasure[$velocity] as $singleMetrics) {
            $metricsHelper = GeneralUtility::makeInstance($singleMetrics);
            if ($velocity != '' && $metricsHelper->getVelocity()) {
                $this->metricsToWork[] = $metricsHelper;
            }
        }
    }

    protected function getValuesAndWriteToDb()
    {
        foreach ($this->metricsToWork as $singleMetrics) {
            $dataToInsert = $singleMetrics->getMetricsValues();
            if (!empty($dataToInsert)) {
                $this->metricsRepository->deleteOldMetricData(array_keys($dataToInsert));
                $this->metricsRepository->saveDataToDb($dataToInsert);
            }
        }
    }
}
