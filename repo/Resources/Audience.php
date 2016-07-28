<?php //-->

namespace Resources;

use Modules\Resource;

/**
 * Resource Audience
 * database object of this class object
 *
 * @category   resource
 * @author     javincX
 */
class Audience
{
    /* Constants
    --------------------------------------------*/
    /* Public Properties
    --------------------------------------------*/
    public static $required = array(
        'create' => array(
            'location',
            'gender',
            'age_min',
            'age_max'));

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
