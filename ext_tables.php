<?php

defined('TYPO3_MODE') or die('Access denied.');

call_user_func(function () {
    /** @var \TYPO3\CMS\Core\Configuration\ExtensionConfiguration $extensionConfiguration */
    $extensionConfiguration = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
        \TYPO3\CMS\Core\Configuration\ExtensionConfiguration::class
    );
    $showAdministrationModule = (bool)$extensionConfiguration->get('prometheus', 'showAdministrationModule');
    if ($showAdministrationModule == true) {
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
                'icon' => 'EXT:prometheus/Resources/Public/Icons/Icon.svg',
                'labels' => 'LLL:EXT:prometheus/Resources/Private/Language/locallang_mod.xlf',
            ]
        );
    }
});
