<?php

namespace mindbird\NotificationCenter\Table;

use Contao\Backend;
use Mailjet\Client;
use \Mailjet\Resources;

class Message extends Backend
{
    public function listMailjetTemplates($datacontainer)
    {
        $message  = \NotificationCenter\Model\Message::findBy('id', $datacontainer->id);
        $options = array();
        if ($message) {
            $gateway = $message->getRelated('gateway');
            if ($gateway->type == 'mailjet') {
                $mailjet = new Client($gateway->mailjet_apikey_public, $gateway->mailjet_apikey_private);
                $filters = [
                    'OwnerType' => 'user'
                ];
                $response = $mailjet->get(Resources::$Template,['filters' => $filters]);
                if ($response->success())
                    foreach ($response->getData() as $template) {
                        $options[$template['ID']] = $template['Name'];
                    }
                else
                    dump($response->getStatus());
            }

        }
        return $options;
    }
}