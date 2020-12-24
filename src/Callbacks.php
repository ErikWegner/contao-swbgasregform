<?php

namespace Contao\SwbGasRegForm;

use Contao\MemberModel;

class Callbacks {
    public static function getFEUsersOptions() {
        $arrReturn = array();
        $users = MemberModel::findAll();
        foreach($users as $user) {
            $arrReturn[$user->id] = $user->firstname. ' ' . $user->lastname . '(' . $user->id . ')';
        }
        
        return $arrReturn;
    }
}
