<?php

namespace Services\User;

use Modules\Auth;
use Modules\Facebook;
use Modules\Helper;
use Services\Me as m;
use Services\User;
use Resources\UserFacebook;



class Fbpage {

    const FB_URL = 'http://graph.facebook.com';

    /* Constants
      -------------------------------------------- */
    /* Public Properties
      -------------------------------------------- */

    // public $auth = false;

    /* Protected Properties
      -------------------------------------------- */
    /* Public Methods
      -------------------------------------------- */

    public static function getfacebookpages($userId, $token)
    {
        //$pages = Facebook::getPages($fbID);
        //$user = m::get(1000000010);
//        $user = Facebook::getUser($token);
//        if($userId != $fbUser['id']) {
//            return Helper::error('FB_LOGIN_INVALID',
//                'facebook authentication failed');
//        }
        //$token = Facebook::getAccessToken($fbID);
//        Facebook::init();
//        if (empty($userId)) {
//            $userId = Auth::getUser()['id'];
//        }
//
//        $user = User::get($userId);
//        print_r($user);
//        if (empty($user)) {
//            return Helper::error('ME_USER_NOT_FOUND', 'user is not found');
//        }

        return $userId;
    }

    /* Protected Methods
      -------------------------------------------- */
    /* Private Methods
      -------------------------------------------- */
}
