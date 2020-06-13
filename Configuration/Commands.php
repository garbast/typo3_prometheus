<?php

return [
    'prometheus:fast' => [
        'class' => \Mfc\Prometheus\Command\FastMetricsCommand::class,
    ],
    'prometheus:medium' => [
        'class' => \Mfc\Prometheus\Command\MediumMetricsCommand::class,
    ],
    'prometheus:slow' => [
        'class' => \Mfc\Prometheus\Command\SlowMetricsCommand::class,
    ],
];
