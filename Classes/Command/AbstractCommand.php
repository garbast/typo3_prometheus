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

namespace Mfc\Prometheus\Command;

use Mfc\Prometheus\Domain\Repository\MetricsRepository;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class AbstractCommand extends \Symfony\Component\Console\Command\Command
{
    /**
     * @var array
     */
    protected $metricsToWork = [];

    /**
     * @var MetricsRepository
     */
    protected $metricsRepository;

    /**
     * Configure the command by defining the name, options and arguments
     */
    protected function configure()
    {
        $this->metricsRepository = GeneralUtility::makeInstance(MetricsRepository::class);
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
        /** @var $singleMetrics \Mfc\Prometheus\Services\Metrics\MetricsInterface $singleMetrics */
        foreach ($this->metricsToWork as $singleMetrics) {
            $dataToInsert = $singleMetrics->getMetricsValues();
            if (!empty($dataToInsert)) {
                $this->metricsRepository->deleteOldMetricData(array_keys($dataToInsert));
                $this->metricsRepository->insertMetrics($dataToInsert);
            }
        }
    }
}
