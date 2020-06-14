<?php

return [
    'frontend' => [
        'mfc/prometheus/exporter' => [
            'target' => \Mfc\Prometheus\Middleware\Exporter::class,
            'after' => [
                'typo3/cms-core/normalized-params-attribute',
            ],
            'before' => [
                'typo3/cms-frontend/eid',
            ],
        ]
    ]
];
