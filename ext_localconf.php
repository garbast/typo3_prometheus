<?php

call_user_func(function () {
    $GLOBALS['TYPO3_CONF_VARS']['FE']['eID_include']['prometheus_metrics'] =
        \Mfc\Prometheus\Eid\Metrics::class . '::processRequest';

    $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['prometheus']['metricsToMeasure'][
        \Mfc\Prometheus\Services\Metrics\MetricsInterface::FAST
    ] = [
        \Mfc\Prometheus\Services\Metrics\FeSessionsMetrics::class,
        \Mfc\Prometheus\Services\Metrics\FeUsersMetrics::class,
        \Mfc\Prometheus\Services\Metrics\BeSessionsMetrics::class,
        \Mfc\Prometheus\Services\Metrics\BeUsersMetrics::class,
        \Mfc\Prometheus\Services\Metrics\SysLockedRecordsMetrics::class,
        \Mfc\Prometheus\Services\Metrics\CfCachePagesMetrics::class,
        \Mfc\Prometheus\Services\Metrics\CfCachePagesTagsMetrics::class,
        \Mfc\Prometheus\Services\Metrics\PowermailMetrics::class,
    ];

    $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['prometheus']['metricsToMeasure'][
        \Mfc\Prometheus\Services\Metrics\MetricsInterface::MEDIUM
    ] = [
        \Mfc\Prometheus\Services\Metrics\SysLogMetrics::class,
    ];

    $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['prometheus']['metricsToMeasure'][
        \Mfc\Prometheus\Services\Metrics\MetricsInterface::SLOW
    ] = [
        \Mfc\Prometheus\Services\Metrics\PagesMetrics::class,
        \Mfc\Prometheus\Services\Metrics\TtContentMetrics::class,
    ];
});
