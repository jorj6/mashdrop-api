<?php //-->

namespace Services;

use Resources\Country as C;
use Modules\Helper;

/**
 * Service Country
 * business logic of this class object
 *
 * @category   service
 * @author     javincX
 */
class Country
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
            return Helper::error('LOCATION_ALREADY_EXISTS',
                'country cant be duplicated');
        }

        return C::create($payload);
    }

    /* Protected Methods
    --------------------------------------------*/
    /* Private Methods
    --------------------------------------------*/
}
