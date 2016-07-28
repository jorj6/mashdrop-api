<?php

//-->

namespace Services\Influence;

use Modules\Helper;
use Modules\Resource;
use Resources\Charities as SMC;

/**
 * Service Category
 * business logic of this class object
 *
 * @category   service
 * @author     javincX
 */
class Charities {
    /* Constants
      -------------------------------------------- */
    /* Public Properties
      -------------------------------------------- */
//        public static $required = array(
//        'create' => array(
//            'name'
//            ));

    /* Protected Properties
      -------------------------------------------- */
    /* Private Properties
      -------------------------------------------- */
    /* Public Methods
      -------------------------------------------- */
   public static function __callStatic($name, $args)
    {
        return SMC::$name(current($args), end($args));
    }
    public static function get($options=array()) {
      
        return SMC::get(array('name'));
      
    }

    /* Protected Methods
      -------------------------------------------- */
    /* Private Methods
      -------------------------------------------- */
}
