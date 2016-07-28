<?php //-->

namespace Services;

use Resources\Photo as P;

/**
 * Service Photo
 * business logic of this class object
 *
 * @category   service
 * @author     javincX
 */
class Photo
{
    /* Constants
    --------------------------------------------*/
    /* Public Properties
    --------------------------------------------*/
    public static $required = array(
        'create' => array(
            'file_id'));

    /* Protected Properties
    --------------------------------------------*/
    /* Private Properties
    --------------------------------------------*/
    /* Public Methods
    --------------------------------------------*/
    public static function __callStatic($name, $args)
    {
        return P::$name(current($args), end($args));
    }

    /* Protected Methods
    --------------------------------------------*/
    /* Private Methods
    --------------------------------------------*/
}
