<?php //-->

namespace Services;

use Resources\Category as C;
use Modules\Helper;

/**
 * Service Category
 * business logic of this class object
 *
 * @category   service
 * @author     javincX
 */
class Category
{
    /* Constants
    --------------------------------------------*/
    /* Public Properties
    --------------------------------------------*/
    public static $required = array(
        'create' => array(
            'name'));
            
    /* Protected Properties
    --------------------------------------------*/
    /* Private Properties
    --------------------------------------------*/
    /* Public Methods
    --------------------------------------------*/
    public static function __callStatic($name, $args)
    {
        return C::$name(current($args), end($args));
    }

    public static function create($payload)
    {
        // check if exists
        if(C::get(array('filters' => array(
            'name' => trim($payload['name']))))) {
            return Helper::error('CATEGORY_ALREADY_EXISTS',
                'category cant be duplicated');
        }

        return C::create($payload);
    }

    /* Protected Methods
    --------------------------------------------*/
    /* Private Methods
    --------------------------------------------*/
}
