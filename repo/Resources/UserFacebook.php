<?php //-->

namespace Resources;

use Modules\Resource;

/**
 * Resource UserFacebook
 * database object of this class object
 *
 * @category   resource
 * @author     javincX
 */
class UserFacebook
{
    /* Constants
    --------------------------------------------*/
    /* Public Properties
    --------------------------------------------*/
    public static $relations = array('user');
    
    /* Protected Properties
    --------------------------------------------*/
    /* Public Methods
    --------------------------------------------*/
    public static function __callStatic($name, $args)
    {
        $table = end(explode('\\', get_class()));
        return Resource::$table($name, $args);
    }

    /* Protected Methods
    --------------------------------------------*/
    /* Private Methods
    --------------------------------------------*/
}
