<?php //-->

namespace Services\User;

use Modules\Auth;
use Resources\Transaction as T;

/**
 * Service User Transaction
 * business logic of this class object
 *
 * @category   service
 * @author     javincX
 */
class Transaction
{
    /* Constants
    --------------------------------------------*/
    /* Public Properties
    --------------------------------------------*/
    /* Protected Properties
    --------------------------------------------*/
    /* Private Properties
    --------------------------------------------*/
    /* Public Methods
    --------------------------------------------*/
    public static function __callStatic($name, $args)
    {
        return T::$name(current($args), end($args));
    }

    public static function find($options)
    {
        // require current user
        $user = Auth::getUser();
        $options['filters']['user_id'] = $user['id'];

        // modify result
        return T::find($options);
    }

    public static function get($options)
    {
        return current(self::find($options));
    }

    /* Protected Methods
    --------------------------------------------*/
    /* Private Methods
    --------------------------------------------*/
}
