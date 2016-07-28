<?php //-->

namespace Services\User;

use Modules\Auth;
use Modules\Helper;
use Services\Balance as B;

/**
 * Service User Balance
 * business logic of this class object
 *
 * @category   service
 * @author     javincX
 */
class Balance
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
    public static function find($options)
    {
        // require current user
        $user = Auth::getUser();
        $options['filters']['user_id'] = $user['id'];

        // modify result
        return B::find($options);
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
