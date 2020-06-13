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

use Mfc\Prometheus\Domain\Repository\SysLogRepository;

class SysLogMetrics extends AbstractMetrics
{
    protected $velocity = MetricsInterface::MEDIUM;

    public function getMetricsValues()
    {
        /** @var \Mfc\Prometheus\Domain\Repository\SysLogRepository $sysLogRepository */
        $sysLogRepository = $this->objectManager->get(SysLogRepository::class);

        return $this->prepareDataToInsert($sysLogRepository->getMetricsValues());
    }
}
