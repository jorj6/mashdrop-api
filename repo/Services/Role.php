<?php //-->

namespace Services;

use Resources\Role as R;

/**
 * Service Role
 * business logic of this class object
 *
 * @category   service
 * @author     javincX
 */
class Role
{
    /* Constants
    --------------------------------------------*/
    /* Public Properties
    --------------------------------------------*/
    public static $required = array(
        'create' => array(
            'name',
            'permissions'));
            
    /* Protected Properties
    --------------------------------------------*/
    /* Private Properties
    --------------------------------------------*/
    /* Public Methods
    --------------------------------------------*/
    public static function __callStatic($name, $args)
    {
        return R::$name(current($args), end($args));
    }

    /* Protected Methods
    --------------------------------------------*/
    /* Private Methods
    --------------------------------------------*/
}
