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

namespace Mfc\Prometheus\Controller\Backend;

use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class PrometheusController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    /**
     * Display Grafana Dashboard in TYPO3 BE
     */
    public function getGrafanaContentAction()
    {
        /** @var ExtensionConfiguration $extensionConfiguration */
        $extensionConfiguration = GeneralUtility::makeInstance(ExtensionConfiguration::class);
        $dashboardUrl = $extensionConfiguration->get('prometheus', 'grafanaDashboardUrl');

        echo '
            <iframe  src="' . $dashboardUrl . '" width="100%" height="100%" class="prometheus"/>
        ';
    }
}
