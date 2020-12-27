<?php

namespace Contao\SwbGasRegForm\Model;

use Contao\Model;

/**
 * 
 * @property integer $id
 * @property integer $tstamp
 * @property string $city;
 */
class SgrfFormsModel extends Model {

    /**
     * Table name
     * @var string
     */
    protected static $strTable = 'tl_sgrf_forms';

}
