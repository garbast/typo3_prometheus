<?php

call_user_func(function () {
    $fast = \Mfc\Prometheus\Services\Metrics\MetricsInterface::FAST;
    $medium = \Mfc\Prometheus\Services\Metrics\MetricsInterface::MEDIUM;
    $slow = \Mfc\Prometheus\Services\Metrics\MetricsInterface::SLOW;

    $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['prometheus']['metricsToMeasure'][$fast] = [
        \Mfc\Prometheus\Services\Metrics\BeSessionsMetrics::class,
        \Mfc\Prometheus\Services\Metrics\BeUsersMetrics::class,
        \Mfc\Prometheus\Services\Metrics\CachePagesMetrics::class,
        \Mfc\Prometheus\Services\Metrics\CachePagesTagsMetrics::class,
        \Mfc\Prometheus\Services\Metrics\SysLockedRecordsMetrics::class,
    ];

    $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['prometheus']['metricsToMeasure'][$medium] = [
        \Mfc\Prometheus\Services\Metrics\SysLogMetrics::class,
    ];

    $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['prometheus']['metricsToMeasure'][$slow] = [
        \Mfc\Prometheus\Services\Metrics\PagesMetrics::class,
    ];

    if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('frontend')) {
        $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['prometheus']['metricsToMeasure'][$fast][]
            = \Mfc\Prometheus\Services\Metrics\FeSessionsMetrics::class;
        $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['prometheus']['metricsToMeasure'][$fast][]
            = \Mfc\Prometheus\Services\Metrics\FeUsersMetrics::class;

        $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['prometheus']['metricsToMeasure'][$slow][]
            = \Mfc\Prometheus\Services\Metrics\TtContentMetrics::class;
    }

    if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('powermail')) {
        $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['prometheus']['metricsToMeasure'][$fast][]
            = \Mfc\Prometheus\Services\Metrics\PowermailMetrics::class;
    }
});
