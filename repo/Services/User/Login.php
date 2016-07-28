<?php //-->

namespace Services\User;

use Modules\Jwt;
use Modules\Facebook;
use Modules\Helper;
use Services\Me;
use Services\User;
use Services\Permission;
use Resources\UserFacebook;

/**
 * Service User Login
 * business logic of this class object
 *
 * @category   service
 * @author     javincX
 */
class Login
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
    public static function facebook($userId, $token)
    {
        // $token = Facebook::getAccessToken();
        $fbUser = Facebook::getUser($token);

        // find user facebook
        // if this exists means user already
        // registered
        $exist = UserFacebook::get(array(
            'filters' => array('uid' => $userId)));

        if($userId != $fbUser['id']) {
            return Helper::error('FB_LOGIN_INVALID',
                'facebook authentication failed');
        }

        // check facebook email if exists
        if(!isset($fbUser['email']) && empty($fbUser['email'])) {
            return Helper::error('FB_EMAIL_NOT_FOUND',
                'facebook email not provided');
        }

        // if registered already save the token
        // and facebook info
        if($exist) {
            $user = User::get(array('filters' => array(
                'id' => $exist['user_id'])));

            // in case user not found
            if(empty($user)) {
                Helper::panic('FB_LOGIN_USER_NOT_FOUND',
                    'user not found with Facebook credentials');
            }
        } else {
            // if not yet exists then register it
            $user = User::create(array(
                'first_name' => $fbUser['first_name'],
                'last_name' => $fbUser['last_name'],
                'password' => $fbUser['id'],
                'email' => $fbUser['email']));

            // in case user not found
            if(empty($user)) {
                Helper::panic('FB_LOGIN_USER_CREATE_ERROR',
                    'user create fails');
            }

            // check error
            if(isset($user['error'])) {
                return $user;
            }

            UserFacebook::create(array(
                'user_id' => $user['id'],
                'uid' => $fbUser['id'],
                'link' => $fbUser['link'],
                'gender' => $fbUser['gender'],
                'timezone' => $fbUser['timezone']
            ));
        }

        // add data to token
        $user['facebook'] = array(
            'id' => $fbUser['id'],
            'access_token' => $token);

        return self::process($user);
    }

    public static function basic($user, $pass)
    {
        // hash password
        $exist = User::get(array('filters' => array(
            User::USER_FIELD => $user,
            User::PASS_FIELD => sha1($pass))));

        // invalid
        if(!$exist) {
            return Helper::error('LOGIN_INVALID',
                'invalid ' . User::USER_FIELD . ' or '. User::PASS_FIELD);
        }

        return self::process($exist);
    }

    public static function admin($user, $pass)
    {
        $u = self::basic($user, $pass);
        if(isset($u['error'])) {
            return $u;
        }

        // check if admin type
        if($u['role_id'] == User::ADMIN_TYPE) {
            return $u;
        }

        return Helper::error('LOGIN_INVALID', 'invalid login');
    }

    /* Protected Methods
    --------------------------------------------*/
    /* Private Methods
    --------------------------------------------*/
    private static function process($user)
    {
        // disabled user no login
        if($user[User::STATUS_FIELD] == 'disabled') {
            return Helper::error('LOGIN_DISABLED', 'user is disabled');
        }

        // get user info
        $user = Me::get($user['id']);

        // get permissions
        $permissions = Permission::getAccess($user['role_id']);

        // stack permission
        $user['access'] = array();
        foreach($permissions as $permission) {
            $user['access'][] = $permission['permission']['name'];
        }

        // generate JWT
        $user['token'] = Jwt::encode(array('user' => $user));

        return $user;
    }
}
