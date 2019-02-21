<?php

$GLOBALS['TL_DCA']['tl_nc_message']['fields']['mailjet_template'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_nc_message']['mailjet_template'],
    'inputType' => 'select',
    'eval' => array(
        'mandatory' => true,
        'maxlength' => 255,
        'tl_class' => 'w50',
        'choosen' => true
    ),
    'sql' => "varchar(16) NOT NULL default ''",
    'options_callback' => array(\Mindbird\Contao\MailjetNotification\Table\Message::class, 'listMailjetTemplates')
);

$GLOBALS['TL_DCA']['tl_nc_message']['fields']['mailjet_recipients'] = array
(
    'label' => &$GLOBALS['TL_LANG']['tl_nc_message']['mailjet_recipients'],
    'inputType' => 'text',
    'eval' => array('tl_class' => 'long clr', 'decodeEntities' => true, 'mandatory' => true),
    'sql' => "varchar(255) NOT NULL default ''",
    'save_callback' => array
    (
        array('NotificationCenter\tl_nc_language', 'validateEmailList')
    )
);

$GLOBALS['TL_DCA']['tl_nc_message']['fields']['mailjet_sender_name'] = array
(
    'label' => &$GLOBALS['TL_LANG']['tl_nc_message']['mailjet_sender_name'],
    'exclude' => true,
    'inputType' => 'text',
    'eval' => array('rgxp' => 'nc_tokens', 'decodeEntities' => true, 'maxlength' => 255, 'tl_class' => 'w50'),
    'sql' => "varchar(255) NOT NULL default ''",
);
$GLOBALS['TL_DCA']['tl_nc_message']['fields']['mailjet_sender_address'] = array
(
    'label' => &$GLOBALS['TL_LANG']['tl_nc_message']['mailjet_sender_address'],
    'exclude' => true,
    'inputType' => 'text',
    'eval' => array('rgxp' => 'nc_tokens', 'maxlength' => 255, 'decodeEntities' => true, 'tl_class' => 'w50'),
    'sql' => "varchar(255) NOT NULL default ''",
    'save_callback' => array
    (
        array('NotificationCenter\tl_nc_language', 'validateEmailList')
    )
);

$GLOBALS['TL_DCA']['tl_nc_message']['fields']['mailjet_subject'] = array
(
    'label' => &$GLOBALS['TL_LANG']['tl_nc_message']['mailjet_subject'],
    'exclude' => true,
    'inputType' => 'text',
    'eval' => array('rgxp' => 'nc_tokens', 'tl_class' => 'long clr', 'decodeEntities' => true, 'mandatory' => true),
    'sql' => "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_nc_message']['palettes']['mailjet'] = '{title_legend},title,gateway;{mailjet_legend},mailjet_template,mailjet_sender_name,mailjet_sender_address,mailjet_recipients,mailjet_subject;{publish_legend},published';
