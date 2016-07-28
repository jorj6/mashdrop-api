<?php //-->

namespace Modules;

use Exception;
use Firebase\JWT\JWT as J;

 /**
  * Module Jwt
  * tool, wrapper, and helper of this class object
  *
  * @category   utility
  * @author     javincX
  */
class Jwt
{
    /* Constants
    --------------------------------------------*/
    /* Public Properties
    --------------------------------------------*/
    /* Protected Properties
    --------------------------------------------*/
    private static $leeway = 0;

    /* Private Properties
    --------------------------------------------*/
    /* Public Methods
    --------------------------------------------*/
    public static function encode($payload = null)
    {
        // get settings and append payload
        $setting = self::setting();

        if(!empty($payload)) {
            $setting['payload'] = $setting['payload'] + $payload;
        }

        try {
            return J::encode($setting['payload'], $setting['key']);
        } catch (Exception $e) {
            return false;
        }
    }

    public static function decode($token)
    {
        // check token if exists
        if(empty($token)) {
            return false;
        }

        $setting = self::setting();
        J::$leeway = self::$leeway;

        try {
            $payload = J::decode($token, $setting['key'], $setting['algo']);
            return json_decode(json_encode($payload), true);
        } catch (Exception $e) {
            // Helper::throwError($e->getMessage());

            return false;
        }
    }

    public static function setLeeway($sec = 0)
    {
        self::$leeway = $sec;
    }

    /* Protected Methods
    --------------------------------------------*/
    protected static function setting()
    {
        return Helper::getSetting('jwt');
    }

    /* Private Methods
    --------------------------------------------*/
}
