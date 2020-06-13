<?php

$EM_CONF['prometheus'] = [
    'title' => 'Prometheus TYPO3 connector',
    'description' => '',
    'category' => 'plugin',
    'author' => 'Simon Schmidt',
    'author_email' => 'typo3@marketing-factory.de',
    'module' => '',
    'state' => 'beta',
    'uploadfolder' => 0,
    'createDirs' => '',
    'modify_tables' => '',
    'clearCacheOnLoad' => 0,
    'version' => '0.0.3',
    'constraints' => [
        'depends' => [
            'scheduler' => '',
            'php' => '5.6.7-7.99.99',
            'typo3' => '9.5.19-10.4.99',
        ],
        'conflicts' => [],

    ],
    'autoload' => [
        'psr-4' => [
            'Mfc\\Prometheus\\' => 'Classes/',
        ],
    ],
];
