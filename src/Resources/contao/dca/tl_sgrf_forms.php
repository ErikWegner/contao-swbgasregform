<?php
$GLOBALS['TL_DCA']['tl_sgrf_forms'] = [
    'config' => [
        'dataContainer' => 'Table',
        'enableVersioning' => true,
        'sql' => [
            'keys' => [
                'id' => 'primary',
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
        'city' => [
            'label' => 'Ort',
            'sql' => ['type' => 'string', 'length' => 255, 'default' => '']
        ]
    ]
];