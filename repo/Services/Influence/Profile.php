<?php

//-->

namespace Services\Influence;

use Resources\InfluencerPageProfile as IPP;

/**
 * Service Category
 * business logic of this class object
 *
 * @category   service
 * @author     javincX
 */
class Profile {
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
        return IPP::$name(current($args), end($args));
    }

    public static function create($data) {

        // check duplicate key

        if ($profile = self::isExists($data['link'])) {
            return $profile;
        }
        
//        echo "<pre>"; print_r($data); die;

        $s = IPP::create($data);

        return $s;
    }

    public static function isExists($key) {
        foreach (self::find() as $value) {
            if ($value['link'] === $key) {
                return $value;
            }
        }

        return false;
    }

}
