<?php //-->

namespace Services\User;

use Modules\Auth;
use Services\Campaign as C;

/**
 * Service User Audience
 * business logic of this class object
 *
 * @category   service
 * @author     javincX
 */
class Campaign
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
        return C::$name(current($args), end($args));
    }

    public static function find($options)
    {
        // require current user
        $user = Auth::getUser();
        $options['filters']['user_id'] = $user['id'];

        // modify result
        return C::find($options);
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
