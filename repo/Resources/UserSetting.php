<?php //-->

namespace Resources;

use Modules\Resource;

/**
 * Resource UserSetting
 * database object of this class object
 *
 * @category   resource
 * @author     javincX
 */
class UserSetting
{
    /* Constants
    --------------------------------------------*/
    /* Public Properties
    --------------------------------------------*/
    public static $relations = array(
        'user',
        'setting');
        
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
