<?php

/*
 * This file is part of mindbird/contao-notification_center-mailjet.
 *
 * (c) mindbird <https://www.mindbird.de>
 *
 * @license LGPL-3.0-or-later
 */

namespace Mindbird\Contao\MailjetNotification\Table;

use Contao\Backend;
use Contao\System;
use Mailjet\Client;
use Mailjet\Resources;
use NotificationCenter\Model\Language;

class Message extends Backend
{
    public function listMailjetTemplates($datacontainer)
    {
        $language = Language::findBy('id', $datacontainer->id);
        $options = [];
        if ($language) {
            $gateway = $language->getRelated('pid')->getRelated('gateway');
            if ('mailjet' === $gateway->type) {
                $mailjet = new Client($gateway->mailjet_apikey_public, $gateway->mailjet_apikey_private);
                $filters = [
                    'OwnerType' => 'user',
                ];
                $response = $mailjet->get(Resources::$Template, ['filters' => $filters]);
                if ($response->success()) {
                    foreach ($response->getData() as $template) {
                        $options[$template['ID']] = $template['Name'];
                    }
                } else {
                    System::log($response->getStatus(), __FUNCTION__, __CLASS__);
                }
            }
        }

        return $options;
    }
}
