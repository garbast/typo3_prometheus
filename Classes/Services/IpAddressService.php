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

namespace Mfc\Prometheus\Services;

use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class IpAddressService
{
    /**
     * @param string $address
     *
     * @return bool
     */
    public function ipInAllowedRange($address = '')
    {
        if (empty($address)) {
            $address = GeneralUtility::getIndpEnv('REMOTE_ADDR');
        }

        /** @var ExtensionConfiguration $extensionConfiguration */
        $extensionConfiguration = GeneralUtility::makeInstance(ExtensionConfiguration::class);
        $allowedIpRanges = $extensionConfiguration->get('prometheus', 'allowedIpRanges');

        if (GeneralUtility::validIPv4($address)) {
            $addressAllowed = GeneralUtility::cmpIPv4($address, $allowedIpRanges);
        } elseif (GeneralUtility::validIPv6($address)) {
            $addressAllowed = GeneralUtility::cmpIPv6($address, $allowedIpRanges);
        } else {
            $addressAllowed = false;
        }

        return $addressAllowed;
    }
}
