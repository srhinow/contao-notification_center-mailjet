<?php

/*
 * This file is part of mindbird/contao-notification_center-mailjet.
 *
 * (c) mindbird <https://www.mindbird.de>
 *
 * @license LGPL-3.0-or-later
 */

namespace Mindbird\Contao\MailjetNotification\Gateway;

use Contao\CoreBundle\Monolog\ContaoContext;
use Contao\System;
use Mailjet\Client;
use Mailjet\Resources;
use Mindbird\Contao\MailjetNotification\MessageDraft\MailjetMessageDraft;
use NotificationCenter\Gateway\Base;
use NotificationCenter\Gateway\GatewayInterface;
use NotificationCenter\MessageDraft\MessageDraftFactoryInterface;
use NotificationCenter\MessageDraft\MessageDraftInterface;
use NotificationCenter\Model\Gateway;
use NotificationCenter\Model\Language;
use NotificationCenter\Model\Message;
use NotificationCenter\Util\StringUtil;
use Psr\Log\LogLevel;

class Mailjet extends Base implements GatewayInterface, MessageDraftFactoryInterface
{
    public function send(Message $objMessage, array $arrTokens, $strLanguage = '')
    {
        $gateway = Gateway::findBy('id', $objMessage->gateway);
        $draft = $this->createDraft($objMessage, $arrTokens, $strLanguage);
        $this->sendDraft($draft, $gateway, $objMessage, $token);
    }

    /**
     * Creates a MessageDraft
     * @param Message
     * @param array
     * @param string
     * @return  MessageDraftInterface|null (if no draft could be found)
     */
    public function createDraft(Message $objMessage, array $arrTokens, $strLanguage = '')
    {
        if ($strLanguage == '') {
            $strLanguage = $GLOBALS['TL_LANGUAGE'];
        }
        if (($objLanguage = Language::findByMessageAndLanguageOrFallback($objMessage, $strLanguage)) === null) {
            \System::log(sprintf('Could not find matching language or fallback for message ID "%s" and language "%s".', $objMessage->id, $strLanguage), __METHOD__, TL_ERROR);
            return null;
        }
        return new MailjetMessageDraft($objMessage, $objLanguage, $arrTokens);
    }

    /**
     * @param MailjetMessageDraft $objDraft
     * @return bool
     * @throws \Exception
     */
    public function sendDraft(MailjetMessageDraft $objDraft, Gateway $gateway, Message $message, $token)
    {
        $mailjet = new Client($gateway->mailjet_apikey_public, $gateway->mailjet_apikey_private);
        $recipients = [];
        foreach (StringUtil::compileRecipients($message->mailjet_recipients, $token) as $recipient) {
            $recipients[] = ['Email' => $recipient];
        }

        $attachements = [];

        // Add file attachments
        $arrAttachments = $objDraft->getAttachments();
        if (!empty($arrAttachments)) {
            $attachements = array_merge($attachements, $arrAttachments);
        }
        // Add string attachments
        $arrAttachments = $objDraft->getStringAttachments();
        if (!empty($arrAttachments)) {
            $attachements = array_merge($attachements, $arrAttachments);
        }

        $body = [
            'FromEmail' => $message->mailjet_sender_address,
            'FromName' => $message->mailjet_sender_name,
            'Subject' => $message->mailjet_subject,
            'MJ-TemplateErrorReporting' => $GLOBALS['TL_CONFIG']['adminEmail'],
            'MJ-TemplateID' => $message->mailjet_template,
            'MJ-TemplateLanguage' => true,
            'Recipients' => $recipients,
            'Vars' => $token,
            'Attachments' => $attachements
        ];
        $response = $mailjet->post(Resources::$Email, ['body' => $body]);
        if (!$response->success()) {
            $logger = System::getContainer()->get('monolog.logger.contao');
            $logger->log(LogLevel::ERROR, $response, array('contao' => new ContaoContext(__FUNCTION__, __CLASS__)));
            return false;
        }

        return true;
    }
}
