<?php //-->

namespace Services\User;

use Modules\Auth;
use Services\Campaign\Post as P;

/**
 * Service User Audience
 * business logic of this class object
 *
 * @category   service
 * @author     javincX
 */
class Post
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
        return P::$name(current($args), end($args));
    }

    public static function find($options)
    {
        // require current user
        $user = Auth::getUser();
        $options['filters']['user_id'] = $user['id'];

        return P::find($options);
    }

    public static function get($options)
    {
        return current(self::find($options));
    }

    public static function findByType($type, $options = array())
    {
        $user = Auth::getUser();
        $options['filters']['user_id'] = $user['id'];

        return P::findByType($type, $options);
    }

    /* Protected Methods
    --------------------------------------------*/
    /* Private Methods
    --------------------------------------------*/
}
