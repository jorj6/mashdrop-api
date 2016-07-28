<?php //-->

namespace Resources;

use Modules\Resource;

/**
 * Resource User
 * database object of this class object
 *
 * @category   resource
 * @author     javincX
 */
class User
{
    /* Constants
    --------------------------------------------*/
    /* Public Properties
    --------------------------------------------*/
    public static $relations = array('role');

    /* Protected Properties
    --------------------------------------------*/
    /* Private Properties
    --------------------------------------------*/
    /* Public Methods
    --------------------------------------------*/
    public static function __callStatic($name, $args)
    {
        $table = end(explode('\\', get_class()));
        return Resource::$table($name, $args);
    }

    public static function find($options)
    {
        $result = self::i()->find($options);
        foreach($result as $key => $user) {
            unset($result[$key]['password']);
        }

        return $result;
    }

    public static function baseGet($options)
    {
        return self::i()->get($options);
    }

    public static function get($options)
    {
        $result = self::baseGet($options);
        unset($result['password']);

        return $result;
    }

    /* Protected Methods
    --------------------------------------------*/
    /* Private Methods
    --------------------------------------------*/
}
