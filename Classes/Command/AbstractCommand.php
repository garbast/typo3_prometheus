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
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class AbstractCommand extends \Symfony\Component\Console\Command\Command
{
    /**
     * @var string
     */
    protected $velocity = '';

    /**
     * @var array
     */
    protected $metricsToMeasure = [];

    /**
     * Configure the command by defining the name, options and arguments
     */
    protected function configure()
    {
        $this->setDescription('Prepare ' . $this->velocity . ' metrics');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->prepareMetrics($this->velocity);
        $this->getValuesAndWriteToDb();
    }

    protected function prepareMetrics($velocity = '')
    {
        $metricsToMeasure = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['prometheus']['metricsToMeasure'][$velocity];
        foreach ($metricsToMeasure as $metricToMeasure) {
            $metricsHelper = GeneralUtility::makeInstance($metricToMeasure);
            if ($velocity != '' && $metricsHelper->getVelocity()) {
                $this->metricsToMeasure[] = $metricsHelper;
            }
        }
    }

    protected function getValuesAndWriteToDb()
    {
        /** @var MetricsRepository $metricsRepository */
        $metricsRepository = GeneralUtility::makeInstance(MetricsRepository::class);
        /** @var $metricToMeasure \Mfc\Prometheus\Services\Metrics\MetricsInterface $singleMetrics */
        foreach ($this->metricsToMeasure as $metricToMeasure) {
            $dataToInsert = $metricToMeasure->getMetricsValues();
            if (!empty($dataToInsert)) {
                $metricsRepository->deleteOldMetricData(array_keys($dataToInsert));
                $metricsRepository->insertMetrics($dataToInsert);
            }
        }
    }
}
