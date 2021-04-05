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

// Add fields to tl_member
$GLOBALS['TL_DCA']['tl_member']['fields']['swbCompany'] = array
(
    'exclude'                 => true,
    'inputType'               => 'select',
    'foreignKey'              => 'tl_swb_company.title',
    'eval'                    => array('includeBlankOption'=>true, 'chosen'=>true, 'tl_class'=>'w50'),
    'sql'                     => "int(10) unsigned NOT NULL default 0"
);

// Extend default palette
Contao\CoreBundle\DataContainer\PaletteManipulator::create()
    ->addLegend('company_legend', 'personal_legend', Contao\CoreBundle\DataContainer\PaletteManipulator::POSITION_BEFORE)
    ->addField('swbCompany', 'company_legend', Contao\CoreBundle\DataContainer\PaletteManipulator::POSITION_APPEND)
    ->applyToPalette('default', 'tl_member');


// Extend default palette
Contao\CoreBundle\DataContainer\PaletteManipulator::create()
    ->addLegend('sgrf_legend', 'personal_legend', Contao\CoreBundle\DataContainer\PaletteManipulator::POSITION_BEFORE)
    ->addField('sgrfausweis', 'sgrf_legend', Contao\CoreBundle\DataContainer\PaletteManipulator::POSITION_APPEND)
    ->applyToPalette('default', 'tl_member');
