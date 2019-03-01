<?php

var_dump($GLOBALS['NOTIFICATION_CENTER']['GATEWAY']);
$GLOBALS['NOTIFICATION_CENTER']['GATEWAY']['mailjet'] = Mindbird\Contao\MailjetNotification\Gateway\Mailjet::class;
var_dump($GLOBALS['NOTIFICATION_CENTER']['GATEWAY']);
