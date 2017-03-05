<?php

$GLOBALS['TL_DCA']['tl_nc_message']['fields']['mailjet_template'] = array(
    'load_callback' => array(
        array('mindbird\NotificationCenter\Table\Message', 'listMailjetTemplates'))
);

$GLOBALS['TL_DCA']['tl_nc_message']['palettes']['mailjet'] = '{title_legend},title,gateway;{mailjet_legend},mailjet_template;{publish_legend},published';