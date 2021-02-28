<?php

$GLOBALS['TL_DCA']['tl_member']['fields']['sgrfausweis'] = [
    'exclude' => true,
    'search' => true,
    'inputType' => 'text',
    'eval' => array('maxlength' => 255, 'feEditable' => false, 'feViewable' => true, 'tl_class' => 'w50'),
    'sql' => "varchar(255) NOT NULL default ''"
    ,
    ]
;

$GLOBALS['TL_DCA']['tl_member']['palettes']['default'] .= ';{sgrf_legend},sgrfausweis';
