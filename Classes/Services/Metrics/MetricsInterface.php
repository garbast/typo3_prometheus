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

/**
 * Interface MetricsInterface
 */
interface MetricsInterface
{
    /**
     * @var string
     */
    public const SLOW = 'slow';

    /**
     * @var string
     */
    public const MEDIUM = 'medium';

    /**
     * @var string
     */
    public const FAST = 'fast';

    /**
     * @return string
     */
    public function getVelocity();

    /**
     * @return array
     */
    public function getMetricsValues();
}
