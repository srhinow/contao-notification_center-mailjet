<?php

namespace mindbird\NotificationCenter\Gateway;

use Contao\System;
use Mailjet\Client;
use Mailjet\Resources;
use NotificationCenter\Gateway\GatewayInterface;
use NotificationCenter\Model\Gateway;
use NotificationCenter\Model\Message;
use NotificationCenter\Util\StringUtil;
use Psr\Log\LogLevel;

class Mailjet implements GatewayInterface
{

    public function send(Message $objMessage, array $arrTokens, $strLanguage = '')
    {
        $gateway = Gateway::findBy('id', $objMessage->gateway);
        $vars = array();
        foreach ($arrTokens as $key => $value) {
            if (preg_match_all('/form_/', $key)) {
                $vars[$key] = $value;
            }
        }

        $mailjet = new Client($gateway->mailjet_apikey_public, $gateway->mailjet_apikey_private);
        $recipients = array();
        foreach (StringUtil::compileRecipients($objMessage->mailjet_recipients, $arrTokens) as $recipient) {
            $recipients[] = ['Email' => $recipient];
        }

        $body = [
            'FromEmail' => $objMessage->mailjet_sender_address,
            'FromName' => $objMessage->mailjet_sender_name,
            'Subject' => $objMessage->mailjet_subject,
            'MJ-TemplateID' => $objMessage->mailjet_template,
            'MJ-TemplateLanguage' => true,
            'Recipients' => $recipients,
            'Vars' => $vars
        ];
        $response = $mailjet->post(Resources::$Email, ['body' => $body]);
        if (!$response->success()) {
            dump($response);
        }
    }
}