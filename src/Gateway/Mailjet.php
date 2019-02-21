<?php

/*
 * This file is part of mindbird/contao-notification_center-mailjet.
 *
 * (c) mindbird <https://www.mindbird.de>
 *
 * @license LGPL-3.0-or-later
 */

namespace Mindbird\Contao\MailjetNotification\Gateway;

use Mailjet\Client;
use Mailjet\Resources;
use NotificationCenter\Gateway\GatewayInterface;
use NotificationCenter\Model\Gateway;
use NotificationCenter\Model\Message;
use NotificationCenter\Util\StringUtil;

class Mailjet implements GatewayInterface
{
    public function send(Message $objMessage, array $arrTokens, $strLanguage = '')
    {
        $gateway = Gateway::findBy('id', $objMessage->gateway);
        $vars = [];
        foreach ($arrTokens as $key => $value) {
            if (preg_match_all('/form_/', $key)) {
                $vars[$key] = $value;
            }
        }

        $mailjet = new Client($gateway->mailjet_apikey_public, $gateway->mailjet_apikey_private);
        $recipients = [];
        foreach (StringUtil::compileRecipients($objMessage->mailjet_recipients, $arrTokens) as $recipient) {
            $recipients[] = ['Email' => $recipient];
        }

        $body = [
            'FromEmail' => $objMessage->mailjet_sender_address,
            'FromName' => $objMessage->mailjet_sender_name,
            'Subject' => $objMessage->mailjet_subject,
            'MJ-TemplateErrorReporting' => $GLOBALS['TL_CONFIG']['adminEmail'],
            'MJ-TemplateID' => $objMessage->mailjet_template,
            'MJ-TemplateLanguage' => true,
            'Recipients' => $recipients,
            'Vars' => $vars,
        ];
        $response = $mailjet->post(Resources::$Email, ['body' => $body]);
        if (!$response->success()) {
            dump($response);
        }
    }
}
