<?php //-->

namespace Modules;

use Exception;
use Predis\Client;
use Predis\ClientException;

 /**
  * Module Cache using Redis server
  * tool, wrapper, and helper of this class object
  *
  * @category   utility
  * @author     javincX
  */
class Cache
{
    /* Constants
    --------------------------------------------*/
    /* Public Properties
    --------------------------------------------*/
    /* Protected Properties
    --------------------------------------------*/
    private static $expiration = 3600;

    /* Private Properties
    --------------------------------------------*/
    /* Public Methods
    --------------------------------------------*/
    public static function db()
    {
        return new Client(self::setting());
    }

    public static function get($key)
    {
        try {
            return self::db()->get($key);
        } catch (Exception $e) {
            // if cache engine fails works normaly
            return;
        }
    }

    public static function set($key, $value, $exp = null)
    {
        $setting = Helper::getSetting('cache');

        if($exp == null) {
            $exp = isset($setting['expiration'])
                ? $setting['expiration'] : self::$expiration;
        }

        try {
            self::db()->set($key, $value, 'ex', $exp);
        } catch (Exception $e) {
            // if cache engine fails works normaly
            return;
        }
    }

    /* Protected Methods
    --------------------------------------------*/
    protected static function setting()
    {
        return Helper::getDatabase('cache');
    }

    /* Private Methods
    --------------------------------------------*/
}
