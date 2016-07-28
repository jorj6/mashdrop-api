<?php

//-->

namespace Services\Influence;

use Resources\SocialMediaCategory as SMC;

/**
 * Service Category
 * business logic of this class object
 *
 * @category   service
 * @author     javincX
 */
class Category {
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

    public static function __callStatic($name, $args) {
        return SMC::$name(current($args), end($args));
    }
    

//    public static function create($payload) {
//        
//        //$user = Auth::getUser();
//
//        // check duplicate key
//        if ($category = self::isExists($payload['name'])) {
//            return $category;
//        }
//
//        $s = SMC::create(array(
//                    'key' => 'test'
//        ));
//        
//        return $s;
//    }
//
//    public static function isExists($key) {
//        foreach (self::find() as $value) {
//            if ($value['name'] === $key) {
//                return $value;
//            }
//        }
//
//        return false;
//    }

}
