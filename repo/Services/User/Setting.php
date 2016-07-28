<?php //-->

namespace Services\User;

use Modules\Auth;
use Modules\Helper;
use Resources\Setting as S;
use Resources\UserSetting as U;

/**
 * Service User Setting
 * business logic of this class object
 *
 * @category   service
 * @author     javincX
 */
class Setting
{
    /* Constants
    --------------------------------------------*/
    const PROFILE_PICTURE_KEY = 'picture';

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
        return U::$name(current($args), end($args));
    }

    public static function find($options)
    {
        // get current users setting
        $user = Auth::getUser();
        return self::getByUserId($user['id'], $options);
    }

    public static function get($options)
    {
        return current(self::find($options));
    }

    public static function create($payload)
    {
       //echo "<pre>"; print_r($payload); die;
        
        $user = Auth::getUser();

        // check duplicate key
        if($setting = self::isExists($payload['key'])) {
            return $setting;
        }
        $s = S::create($payload);
        U::create(array(
            'user_id' => $user['id'],
            'setting_id' => $s['id']));

        return $s;
    }

    public static function update($payload, $id)
    {
       
        // check if exists
        $s = U::get($id);
        if(empty($s)) {
            return Helper::error('USER_SETTING_NOT_FOUND',
                'user setting not exists');
        }

        return S::update($payload, $s['setting_id']);
    }

    public static function remove($id)
    {
        // check if exists
        $s = U::get($id);
        if(empty($s)) {
            return Helper::error('USER_SETTING_NOT_FOUND',
                'user setting not exists');
        }

        U::remove($id);
        return S::remove($s['setting_id']);
    }

    public static function getByUserId($userId, $options = array())
    {
        // search for filters key
        $keySetting = null;
        if(isset($options['filters']['key'])) {
            $keySetting = $options['filters']['key'];
            unset($options['filters']['key']);
        }

        $options['filters']['user_id'] = $userId;
        $options['fields'] = array('id', 'setting_id');

        // get setting only
        $data = array();
        $result = U::find($options);
        foreach($result as $key => $value) {
            // check if exists
            $settingOptions = array();
            $settingOptions['filters']['id'] = $value['setting_id'];
            if($keySetting) {
                $settingOptions['filters']['key'] = $keySetting;
            }

            $setting = S::get($settingOptions);
            if(empty($setting)) {
                unset($result[$key]);
                continue;
            }

            $setting['id'] = $value['id'];
            $data[] = $setting;
        }
        

        return $data;
    }

    /* Protected Methods
    --------------------------------------------*/
    /* Private Methods
    --------------------------------------------*/
    public static function isExists($key)
    {
        foreach(self::find() as $value) {
            if($value['key'] === $key) {
                return $value;
            }
        }

        return false;
    }
}
