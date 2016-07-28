<?php

namespace Api\Page\Me;

use Modules\Auth;
use Modules\Facebook;
use Modules\Helper;
use Services\Me as m;
use Services\User;
use Services\User\Fbpage as fbp;
use Resources\UserFacebook;

class Fbpage extends \Page {

    public $auth = false;

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

    public function getVariables() {
        $userId = Helper::getSegment(0);
        
        $token = Helper::getSegment(1);
       // $user = m::get($userId);
//        $fbID= $user['facebook']['uid'];
        $FBPages = fbp::getfacebookpages($userId, $token);
//        Helper::debug($token);
        
       // $token = Facebook::getAccessToken();
//        if (empty($userId)) {
//            $userId = Auth::getUser()['id'];
//        }
//        
//
//        $user = User::get($userId);
//        print_r($user);
//        if (empty($user)) {
//            return Helper::error('ME_USER_NOT_FOUND', 'user is not found');
//        }

//        if (Helper::getRequestMethod() == 'GET') {
//           $userId = Auth::getUser()['id'];
//           return "aaa=".$userId;
//        }
        return $FBPages;
    }

    /* Protected Methods
      -------------------------------------------- */
    /* Private Methods
      -------------------------------------------- */
}
