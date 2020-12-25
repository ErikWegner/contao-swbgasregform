<?php

$GLOBALS['TL_DCA']['tl_sgrf_roles'] = [
    'config' => [
        'dataContainer' => 'Table',
        'enableVersioning' => true,
        'sql' => [
            'keys' => [
                'id' => 'primary',
            ],
        ],
    ],
    'list' => [
        'sorting' => [
            'mode' => 1,
            'fields' => ['fuser'],
            'flag' => 11,
            'panelLayout' => 'search,limit;filter',
            'disableGrouping' => true,
        ],
        'label' => [
            'fields' => ['fuser', "fuser:tl_member.CONCAT(firstname, ' ', lastname, ' (', id, ')')", 'name', 'installateur', 'bsf'],
            'format' => '%s',
            'showColumns' => true,
        ],
        'operations' => [
            'edit' => [
                'href' => 'act=edit',
                'icon' => 'header.svg',
            ],
            'delete' => [
                'href' => 'act=delete',
                'icon' => 'delete.svg',
            ],
            'show' => [
                'href' => 'act=show',
                'icon' => 'show.svg'
            ],
        ],
    ],
    'fields' => [
        'id' => [
            'sql' => ['type' => 'integer', 'unsigned' => true, 'autoincrement' => true],
        ],
        'tstamp' => [
            'sql' => ['type' => 'integer', 'unsigned' => true, 'default' => 0]
        ],
        'fuser' => [
            'label' => &$GLOBALS['TL_LANG']['tl_sgrf_roles']['fuser'],
            'search' => true,
            'inputType' => 'select',
            'foreignKey' => "tl_member.CONCAT(firstname, ' ', lastname, ' (', id, ')')",
            'eval' => ['unique'=>true, 'tl_class' => 'w50', 'mandatory' => true, 'includeBlankOption' => true],
            'sql' => ['type' => 'integer', 'unsigned' => true, 'default' => '0']
        ],
        'installateur' => [
            'label' => &$GLOBALS['TL_LANG']['tl_sgrf_roles']['installateur'],
            'filter' => true,
            'inputType' => 'checkbox',
            'eval' => ['tl_class' => 'w100'],
            'sql' => ['type' => 'string', 'length' => 1, 'default' => '']
        ],
        'bsf' => [
            'label' => &$GLOBALS['TL_LANG']['tl_sgrf_roles']['bsf'],
            'filter' => true,
            'inputType' => 'checkbox',
            'eval' => ['tl_class' => 'w100','submitOnChange'=>true],
            'sql' => ['type' => 'string', 'length' => 1, 'default' => '']
        ],
        'gnb' => [
            'label' => &$GLOBALS['TL_LANG']['tl_sgrf_roles']['gnb'],
            'filter' => true,
            'inputType' => 'checkbox',
            'eval' => ['tl_class' => 'w100'],
            'sql' => ['type' => 'string', 'length' => 1, 'default' => '']
        ],
        'swb' => [
            'label' => &$GLOBALS['TL_LANG']['tl_sgrf_roles']['swb'],
            'filter' => true,
            'inputType' => 'checkbox',
            'eval' => ['tl_class' => 'w100'],
            'sql' => ['type' => 'string', 'length' => 1, 'default' => '']
        ],
        'name' => [
            'label' => &$GLOBALS['TL_LANG']['tl_sgrf_bsf']['name'],
            'search' => true,
            'inputType' => 'text',
            'eval' => ['tl_class' => 'w50', 'maxlength' => 255, 'mandatory' => true],
            'sql' => ['type' => 'string', 'length' => 255, 'default' => '']
        ],
        'active' => [
            'label' => &$GLOBALS['TL_LANG']['tl_sgrf_roles']['active'],
            'filter' => true,
            'inputType' => 'checkbox',
            'eval' => ['tl_class' => 'w50'],
            'sql' => ['type' => 'string', 'length' => 1, 'default' => '']
        ],
    ],
    'palettes' => [
        '__selector__' => ['bsf',],
        'default' => '{sgrf_roles_contao},fuser;{sgrf_roles_roles},installateur,bsf,gnb,swb'
    ],
    'subpalettes' => [
		'bsf' => 'name,active',
	],
];
