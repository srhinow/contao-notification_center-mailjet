<?php

/*
 * This file is part of mindbird/contao-notification_center-mailjet.
 *
 * (c) mindbird <https://www.mindbird.de>
 *
 * @license LGPL-3.0-or-later
 */

namespace Mindbird\Contao\MailjetNotification\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Mindbird\Contao\MailjetNotification\MailjetNotificationBundle;

/**
 * Plugin for the Contao Manager.
 */
class Plugin implements BundlePluginInterface
{
    /**
     * {@inheritdoc}
     */
    public function getBundles(ParserInterface $parser)
    {
        return [
            BundleConfig::create(MailjetNotificationBundle::class)
                ->setLoadAfter([ContaoCoreBundle::class, 'notification_center']),
        ];
    }
}
