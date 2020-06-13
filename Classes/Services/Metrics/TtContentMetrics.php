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

use Mfc\Prometheus\Domain\Repository\TtContentRepository;

class TtContentMetrics extends AbstractMetrics
{
    protected $velocity = MetricsInterface::SLOW;

    public function getMetricsValues()
    {
        /** @var \Mfc\Prometheus\Domain\Repository\TtContentRepository $ttContentRepository */
        $ttContentRepository = $this->objectManager->get(TtContentRepository::class);

        return $this->prepareDataToInsert($ttContentRepository->getMetricsValues());
    }
}
