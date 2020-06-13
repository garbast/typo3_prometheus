<?php

defined('TYPO3_MODE') or die('Access denied.');

/** @var \TYPO3\CMS\Core\Configuration\ExtensionConfiguration $extensionConfiguration */
$extensionConfiguration = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
    \TYPO3\CMS\Core\Configuration\ExtensionConfiguration::class
);
$showAdministrationModule = (bool)$extensionConfiguration->get('prometheus', 'showAdministrationModule');
if ($showAdministrationModule == true) {
    call_user_func(
        function ($extKey) {
            if (TYPO3_MODE === 'BE') {
                \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
                    'Prometheus',
                    'system',
                    'prometheus',
                    '',
                    [
                        \Mfc\Prometheus\Controller\Backend\PrometheusController::class => 'getGrafanaContent',
                    ],
                    [
                        'access' => 'user,group',
                        'icon' => 'EXT:' . $extKey . '/Resources/Public/Icon/Icon.svg',
                        'labels' => 'LLL:EXT:' . $extKey . '/Resources/Private/Language/locallang_mod.xml',
                    ]
                );
            }
        },
        'prometheus'
    );
}
