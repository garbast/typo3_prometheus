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

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SlowMetricsCommand extends AbstractCommand
{
    protected function configure()
    {
        $this->setDescription('Prepare slow metrics');
        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->initializeMetrics(\Mfc\Prometheus\Services\Metrics\MetricsInterface::SLOW);
        $this->getValuesAndWriteToDb();
    }
}