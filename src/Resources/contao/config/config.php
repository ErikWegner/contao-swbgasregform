<?php
$GLOBALS['BE_MOD']['sgrf'] = [
    'roles' => [
        'tables' => ['tl_sgrf_roles'],
        ]
];

$GLOBALS['TL_MODELS']['tl_sgrf_forms'] = Contao\SwbGasRegForm\Model\SgrfFormsModel::class;
