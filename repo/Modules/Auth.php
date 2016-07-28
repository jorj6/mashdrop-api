<?php //-->

namespace Modules;

use Exception;

/**
 * Module Auth
 * tool, wrapper, and helper of this class object
 *
 * @category   module
 * @author     javincX
 */
class Auth
{
    /* Constants
    --------------------------------------------*/
    const ID_FIELD = 'id';
    const USER_KEY = 'user';
    const AUTH_FIELD = 'HTTP_APPLICATION_AUTHORIZATION';

    /* Public Properties
    --------------------------------------------*/
    /* Protected Properties
    --------------------------------------------*/
    public static $errors = array(
        'AUTH_NO_CREDENTIALS' => 'no credentials found',
        'AUTH_INVALID_CREDENTIALS' => 'invalid credentials',
        'AUTH_NO_USER' => 'user not found',
        'ACTION_FORBIDDEN' => 'forbidden action'
    );

    /* Private Properties
    --------------------------------------------*/
    private static $user = null;

    /* Public Methods
    --------------------------------------------*/
    public static function setUser($user) {
        self::$user = $user;
    }

    public static function getUser() {
        return self::$user;
    }

    public static function check()
    {
        // check required
        $token = Helper::getServer('HTTP_APPLICATION_AUTHORIZATION');
        if(empty($token)) {
            self::errorCode('AUTH_NO_CREDENTIALS');
        }

        // validate and get id
        $payload = self::validate($token);
        if(empty($payload)) {
            self::errorCode('AUTH_INVALID_CREDENTIALS');
        }

        // check if user exists
        $user = $payload[self::USER_KEY];
        if(empty($user)) {
            self::errorCode('AUTH_NO_USER');
        }

        return $user;
    }

    public static function errorCode($code)
    {
        if(array_key_exists($code, self::$errors)) {
            // kill it!
            Helper::error($code, self::$errors[$code], true);
        }
    }

    /* Protected Methods
    --------------------------------------------*/
    /* Private Methods
    --------------------------------------------*/
    private static function validate($token)
    {
        try {
            Jwt::setLeeway(60);
            return Jwt::decode($token);
        } catch (Exception $e) {
            return false;
        }
    }
}
