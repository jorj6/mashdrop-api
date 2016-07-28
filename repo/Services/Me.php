<?php //-->

namespace Services;

use Modules\Auth;
use Modules\Helper;
use Services\User\Setting as S;
use Resources\UserFacebook;

/**
 * Service Me
 * business logic of this class object
 *
 * @category   service
 * @author     javincX
 */
class Me
{
    /* Constants
    --------------------------------------------*/
    const FB_URL = 'http://graph.facebook.com';

    /* Public Properties
    --------------------------------------------*/
    /* Protected Properties
    --------------------------------------------*/
    /* Private Properties
    --------------------------------------------*/
    /* Public Methods
    --------------------------------------------*/
    public static function get($userId = null)
    {
        if(empty($userId)) {
            $userId = Auth::getUser()['id'];
        }

        $user = User::get($userId);
        if(empty($user)) {
            return Helper::error('ME_USER_NOT_FOUND',
                'user is not found');
        }

        // get user facebook
        $user['facebook'] = UserFacebook::get(array(
            'filters' => array('user_id' => $user['id'])));

        // set profile picture if fb exists
        $user['picture'] = isset($user['facebook']['uid']) ? implode('/', [
            self::FB_URL, $user['facebook']['uid'], 'picture?type=large']) : null;

        // user picture is null. try getting it from setting
        if(empty($user['picture'])) {
            $setting = S::getByUserId($user['id'], array(
                'filters' => array('key' => S::PROFILE_PICTURE_KEY)));
            $user['picture'] = current($setting)['value'];
        }

        // unset
        // unset($user['role_id']);

        return $user;
    }

    /* Protected Methods
    --------------------------------------------*/
    /* Private Methods
    --------------------------------------------*/
}
