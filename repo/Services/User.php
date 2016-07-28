<?php //-->

namespace Services;

use Modules\Auth;
use Modules\Helper;
use Resources\User as U;
use Resources\UserSetting;

/**
 * Service User
 * business logic of this class object
 *
 * @category   service
 * @author     javincX
 */
class User
{
    /* Constants
    --------------------------------------------*/
    const ADMIN_TYPE = 1;
    //const CLIENT_TYPE = 0;
    const INFLUENCER_TYPE = 2;
    const ADVERTISER_TYPE = 3;

    const USER_FIELD = 'email';
    const PASS_FIELD = 'password';
    const STATUS_FIELD = 'status';

    /* Public Properties
    --------------------------------------------*/
    public static $required = array(
        'create' => array(
            'first_name',
            'last_name',
            'email',
            'password',
             'role_id',
             'phone_number'
            ));

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

    public static function create($data)
    {
        // check if email exists
        if(U::get(array(
            'filters' => array(
                'email' => $data['email']),
            'fields' => array('id')))) {
            return Helper::error('USER_EMAIL_EXISTS',
                '\'' . $data['email'] . '\' email already exists');
        }

        // hash id
        $data['password'] = sha1($data['password']);
       
        // save the user
        $user = U::create($data);
    

        return $user;
    }

    public static function update($data, $filter)
    {
        // check password if exists then update it
        // else dont
        if(isset($data['password']) && trim($data['password']) != '') {
            $data['password'] = sha1($data['password']);
        } else if(trim($data['password']) == '') {
            unset($data['password']);
        }

        // save the user
        $user = U::update($data, $filter);

        return $user;
    }

    public static function getClientById($id, $options = array())
    {
        $options['filters']['id'] = $id;
        $options['filters']['role_id'] = self::INFLUENCER_TYPE;

        return self::get($options);
    }

    public static function findByType($type, $options = array())
    {

        // default will be client
        $roleId = self::INFLUENCER_TYPE;
        switch ($type) {
        case 'admin':
            $roleId = self::ADMIN_TYPE;
            break;

        case 'advertiser':
            $roleId = self::ADVERTISER_TYPE;
            break;   
        }

        $options['filters']['role_id'] = $roleId;

        return self::find($options);
    }

    /* Protected Methods
    --------------------------------------------*/
    /* Private Methods
    --------------------------------------------*/
}
