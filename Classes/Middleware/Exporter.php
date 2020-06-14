<?php

/*
 * This file is developed by CP/COMPARTNER.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Mfc\Prometheus\Middleware;

use Mfc\Prometheus\Domain\Repository\MetricsRepository;
use Mfc\Prometheus\Services\IpAddressService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TYPO3\CMS\Core\Http\Response;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class Exporter implements MiddlewareInterface
{
    /**
     * @var string
     */
    protected $extensionKey = 'prometheus';

    /**
     * Export data for prometheus scraping
     *
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($request->getServerParams()['REQUEST_URI'] !== '/metrics') {
            return $handler->handle($request);
        }

        /** @var Response $response */
        $response = GeneralUtility::makeInstance(Response::class);
        /** @var IpAddressService $ipHelper */
        $ipHelper = GeneralUtility::makeInstance(IpAddressService::class);
        if ($ipHelper->ipInAllowedRange()) {
            /** @var MetricsRepository $metricController */
            $metricController = GeneralUtility::makeInstance(MetricsRepository::class);
            $returnData = implode(PHP_EOL, array_keys($metricController->getAllMetrics())) . PHP_EOL;
            $response->getBody()->write($returnData);

            $response = $response
                ->withHeader('Cache-Control', 'no-cache, must-revalidate')
                ->withHeader('Content-Type', 'text/plain; charset=utf-8')
                ->withHeader('Expires', 'Mon, 26 Jul 1997 05:00:00 GMT')
                ->withHeader('Last-Modified', gmdate('D, d M Y H:i:s') . ' GMT')
                ->withHeader('Pragma', 'no-cache');
        } else {
            $response = $response->withStatus(403, 'Forbidden');
        }

        return $response;
    }
}
