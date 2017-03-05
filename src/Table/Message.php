<?php

namespace mindbird\NotificationCenter\Table;

use Contao\Backend;
use Mailjet\Client;

class Message extends Backend
{
    public function listMailjetTemplates()
    {
        dump(func_get_args());
        $mailjet = new Client($GLOBALS['TL_CONFIG']['be_notification_center_mailjet_apikey_public'], $GLOBALS['TL_CONFIG']['be_notification_center_mailjet_apikey_private']);
        dump($mailjet->get('template'));
    }
}