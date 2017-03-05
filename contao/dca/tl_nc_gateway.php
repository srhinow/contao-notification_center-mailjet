<?php

$GLOBALS['TL_DCA']['tl_nc_gateway']['fields']['mailjet_apikey_private'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_nc_gateway']['mailjet_apikey_private'],
    'exclude' => true,
    'inputType' => 'text',
    'eval' => array(
        'mandatory' => true,
        'maxlength' => 255,
        'tl_class' => 'w50'
    )
);

$GLOBALS['TL_DCA']['tl_nc_gateway']['fields']['mailjet_apikey_public'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_nc_gateway']['mailjet_apikey_public'],
    'exclude' => true,
    'inputType' => 'text',
    'eval' => array(
        'mandatory' => true,
        'maxlength' => 255,
        'tl_class' => 'w50'
    )
);

$GLOBALS['TL_DCA']['tl_nc_gateway']['palettes']['mailjet'] = '{title_legend},title,type;{gateway_legend},mailjet_apikey_public,mailjet_apikey_private';