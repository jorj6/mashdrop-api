<?php

//-->

namespace Resources;

use Modules\Resource;

/**
 * Resource UserPhoto
 * database object of this class object
 *
 * @category   resource
 * @author     javincX
 */
class InfluencerPageProfile {
    /* Constants
      -------------------------------------------- */
    /* Public Properties
      -------------------------------------------- */


    /* Protected Properties
      -------------------------------------------- */
    /* Public Methods
      -------------------------------------------- */

//    public static function __callStatic($name, $args) {
//        $table = end(explode('\\', get_class()));
//        
//        echo "<pre>"; print_r($table); 
//        echo "<pre>"; print_r($args);  print_r($name); die;
//        return Resource::$table($name, $args);
//    }
    
    public static function __callStatic($name, $args)
    {
        $table = end(explode('\\', get_class()));
        return Resource::$table($name, $args);
    }

    /* Protected Methods
      -------------------------------------------- */
    /* Private Methods
      -------------------------------------------- */
}
