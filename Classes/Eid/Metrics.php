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

namespace Mfc\Prometheus\Eid;

use Mfc\Prometheus\Domain\Repository\MetricsRepository;
use Mfc\Prometheus\Services\IpAddressService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class Metrics
{
    public function processRequest()
    {
        /** @var IpAddressService $ipHelper */
        $ipHelper = GeneralUtility::makeInstance(IpAddressService::class);
        if ($ipHelper->ipInAllowedRange()) {
            /** @var MetricsRepository $metricController */
            $metricController = GeneralUtility::makeInstance(MetricsRepository::class);

            $returnData = implode(PHP_EOL, array_keys($metricController->getAllMetrics()));

            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
            header('Cache-Control: no-cache, must-revalidate');
            header('Pragma: no-cache');
            header('Content-Type: text/plain; charset=utf-8');

            echo $returnData . PHP_EOL;
        } else {
            header('HTTP/1.0 403 Forbidden');
        }
    }
}
