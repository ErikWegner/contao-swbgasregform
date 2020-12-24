<?php

$GLOBALS['TL_DCA']['tl_sgrf_bsf'] = [
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
            'fields' => ['name', 'active'],
            'flag' => 1,
            'panelLayout' => 'search,limit'
        ],
        'label' => [
            'fields' => ['name'],
            'format' => '%s',
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
        'name' => [
            'label' => &$GLOBALS['TL_LANG']['tl_sgrf_bsf']['name'],
            'search' => true,
            'inputType' => 'text',
            'eval' => ['tl_class' => 'w50', 'maxlength' => 255, 'mandatory' => true],
            'sql' => ['type' => 'string', 'length' => 255, 'default' => '']
        ],
        'active' => [
            'label' => &$GLOBALS['TL_LANG']['tl_sgrf_bsf']['active'],
            'inputType' => 'checkbox',
            'eval' => ['tl_class' => 'w50'],
            'sql' => ['type' => 'string', 'length' => 1, 'default' => '']
        ],
        'fuser' => [
            'label' => &$GLOBALS['TL_LANG']['tl_sgrf_bsf']['fuser'],
            'inputType' => 'select',
            'foreignKey' => "tl_member.CONCAT(firstname, ' ', lastname, ' (', id, ')')",
            'eval' => ['tl_class' => 'w50', 'mandatory' => true, 'includeBlankOption' => true],
            'sql' => ['type' => 'integer', 'default' => '0']
        ]
    ],
    'palettes' => [
        'default' => '{sgrf_bsf_legend},name,active;{sgrf_bsf_contao},fuser'
    ],
];
