<?php

//-->

namespace Modules;

use Exception;
use Facebook as F;

/**
 * Module Jwt
 * tool, wrapper, and helper of this class object
 *
 * @category   utility
 * @author     javincX
 */
class Facebook {
    /* Constants
      -------------------------------------------- */
    /* Public Properties
      -------------------------------------------- */
    /* Protected Properties
      -------------------------------------------- */
    /* Private Properties
      -------------------------------------------- */
    /* Public Methods
      -------------------------------------------- */

    public static function init() {
        return new F\Facebook(self::setting());
    }

    public static function getAccessToken() {
        $fb = self::init();

        $helper = $fb->getJavaScriptHelper();
        try {
            $accessToken = $helper->getAccessToken();
        } catch (F\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            Helper::panic('FB_GET_TOKEN_RESPONSE_EXCEPTION', $e->getMessage());
        } catch (F\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            Helper::panic('FB_GET_TOKEN_SDK_EXCEPTION', $e->getMessage());
        } catch (Exception $e) {
            Helper::panic('FB_GET_TOKEN_EXCEPTION', $e->getMessage());
        }

        if (!isset($accessToken)) {
            Helper::panic('FB_EXCEPTION', 'No cookie set or no OAuth data could be obtained from cookie.');
        }

        $oAuth2Client = $fb->getOAuth2Client();
        $longLivedAccessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);

        return (string) $longLivedAccessToken;
    }

    public static function getUser($accessToken) {
        try {
            $response = self::init()
                    ->get('/me?fields=first_name,last_name,email,link,gender,timezone', $accessToken);
        } catch (F\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            Helper::panic('FB_GET_USER_RESPONSE_EXCEPTION', $e->getMessage());
        } catch (F\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            Helper::panic('FB_GET_USER_SDK_EXCEPTION', $e->getMessage());
        } catch (Exception $e) {
            Helper::panic('FB_GET_USER_EXCEPTION', $e->getMessage());
        }

        return current($response->getGraphUser());
    }

    public static function getPages($id) {
        
        
//        $request = new FacebookRequest(
//                $session, 'GET', '/'.$id.'/accounts'
//        );
//        $response = $request->execute();
//        $graphObject = $response->getGraphObject();
        return $id;
    }

    /* Protected Methods
      -------------------------------------------- */

    protected static function setting() {
        return Helper::getSetting('facebook');
    }

    /* Private Methods
      -------------------------------------------- */
}
