<?php

//-->

namespace Services\Influence;

use Modules\Helper;
use Modules\Resource;
use Resources\PaypalEmail as PE;

/**
 * Service Category
 * business logic of this class object
 *
 * @category   service
 * @author     javincX
 */
class Paypalemail {
    /* Constants
      -------------------------------------------- */
    /* Public Properties
      -------------------------------------------- */

    /* Protected Properties
      -------------------------------------------- */
    /* Private Properties
      -------------------------------------------- */
    /* Public Methods
      -------------------------------------------- */
   public static function __callStatic($name, $args)
    {
        return RPC::$name(current($args), end($args));
    }
    
    
    public static function create($data)
    {
//        $user = Auth::getUser();
//
//        // check duplicate key
//        if($setting = self::isExists($payload['key'])) {
//            return $setting;
//        }
        
//        echo "<pre>";
//        print_r($data);
//        die();

        $s = PE::create($data);

        return $s;
    }

    /* Protected Methods
      -------------------------------------------- */
    /* Private Methods
      -------------------------------------------- */
}
