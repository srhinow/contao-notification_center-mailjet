<?php
$GLOBALS['TL_DCA']['tl_settings']['fields']['be_notification_center_mailjet_apikey_private'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_settings']['be_notification_center_mailjet_apikey_private'],
    'exclude' => true,
    'inputType' => 'text',
    'eval' => array(
        'mandatory' => true,
        'maxlength' => 255,
        'tl_class' => 'w50'
    )
);
$GLOBALS['TL_DCA']['tl_settings']['fields']['be_notification_center_mailjet_apikey_public'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_settings']['be_notification_center_mailjet_apikey_public'],
    'exclude' => true,
    'inputType' => 'text',
    'eval' => array(
        'mandatory' => true,
        'maxlength' => 255,
        'tl_class' => 'w50'
    )
);
if (substr($GLOBALS['TL_DCA']['tl_settings']['palettes']['default'], - 1) != ';') {
    $GLOBALS['TL_DCA']['tl_settings']['palettes']['default'] .= ';';
}
$GLOBALS['TL_DCA']['tl_settings']['palettes']['default'] .= '{be_notification_center_mailjet_legend},be_notification_center_mailjet_apikey_public,be_notification_center_mailjet_apikey_private;';