<?php

namespace mindbird\NotificationCenter\Gateway;

use Mailjet\Client;
use NotificationCenter\Gateway\GatewayInterface;
use NotificationCenter\Model\Message;


class Mailjet implements GatewayInterface
{

    public function send(Message $objMessage, array $arrTokens, $strLanguage = '')
    {
        $mailjet = new Client($GLOBALS['TL_CONFIG']['be_notification_center_mailjet_apikey_public'], $GLOBALS['TL_CONFIG']['be_notification_center_mailjet_apikey_private']);
        $body = [
            'FromEmail' => "pilot@mailjet.com",
            'FromName' => "Mailjet Pilot",
            'Subject' => "Your email flight plan!",
            'MJ-TemplateID' => 1,
            'MJ-TemplateLanguage' => true,
            'Recipients' => [['Email' => "passenger@mailjet.com"]],
            'Vars' => ''
        ];
        $response = $mailjet->post(Resources::$Email, ['body' => $body]);
        $response->success() && var_dump($response->getData());
    }
}