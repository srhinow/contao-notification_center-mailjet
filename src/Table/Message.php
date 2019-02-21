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
use Mailjet\Client;
use Mailjet\Resources;

class Message extends Backend
{
    public function listMailjetTemplates($datacontainer)
    {
        $message = \NotificationCenter\Model\Message::findBy('id', $datacontainer->id);
        $options = [];
        if ($message) {
            $gateway = $message->getRelated('gateway');
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
                    dump($response->getStatus());
                }
            }
        }

        return $options;
    }
}
