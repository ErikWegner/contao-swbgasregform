<?php

use Contao\SwbGasRegForm\Model\SwbCompanyModel;

$GLOBALS['BE_MOD']['accounts']['swb_company']['tables'] = array('tl_swb_company');
$GLOBALS['TL_MODELS']['tl_swb_company'] = SwbCompanyModel::class;
