<?php

namespace mindbird\NotificationCenter\Table;

use Contao\Backend;
use Mailjet\Client;

class Message extends Backend
{
    public function listMailjetTemplates($value, $datacontainer)
    {
        $message  = \NotificationCenter\Model\Message::findBy('id', $datacontainer->id);
        if ($message) {
            $gateway = $message->getRelated('gateway');
            if ($gateway->type == 'mailjet') {
                dump($gateway);
                $mailjet = new Client($gateway->mailjet_apikey_public, $gateway->mailjet_apikey_private);
                dump($mailjet->get('template'));
            }

        }
    }
}