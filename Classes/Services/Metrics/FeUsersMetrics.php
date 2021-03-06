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

namespace Mfc\Prometheus\Services\Metrics;

class FeUsersMetrics extends AbstractMetrics
{
    protected $repositoryClassName = \Mfc\Prometheus\Domain\Repository\FeUsersRepository::class;

    protected $velocity = MetricsInterface::FAST;
}
