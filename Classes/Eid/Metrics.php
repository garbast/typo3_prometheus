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

namespace Mfc\Prometheus\Eid;

use Mfc\Prometheus\Domain\Repository\MetricsRepository;
use Mfc\Prometheus\Services\IpAddressService;
use TYPO3\CMS\Core\Http\Response;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class Metrics
{
    public function processRequest(): Response
    {
        /** @var Response $response */
        $response = GeneralUtility::makeInstance(Response::class);
        /** @var IpAddressService $ipHelper */
        $ipHelper = GeneralUtility::makeInstance(IpAddressService::class);
        if ($ipHelper->ipInAllowedRange()) {
            /** @var MetricsRepository $metricController */
            $metricController = GeneralUtility::makeInstance(MetricsRepository::class);
            $returnData = implode(PHP_EOL, array_keys($metricController->getAllMetrics())) . PHP_EOL;
            $response->getBody()->write($returnData);

            $response = $response->withHeader('Cache-Control', 'no-cache, must-revalidate');
            $response = $response->withHeader('Content-Type', 'text/plain; charset=utf-8');
            $response = $response->withHeader('Expires', 'Mon, 26 Jul 1997 05:00:00 GMT');
            $response = $response->withHeader('Last-Modified', gmdate('D, d M Y H:i:s') . ' GMT');
            $response = $response->withHeader('Pragma', 'no-cache');
        } else {
            $response = $response->withStatus(403, 'Forbidden');
        }

        return $response;
    }
}
