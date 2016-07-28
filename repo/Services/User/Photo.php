<?php //-->

namespace Services\User;

use Modules\Auth;
use Modules\Helper;
use Modules\Resource;
use Resources\Photo as P;
use Resources\UserPhoto as U;

/**
 * Service User Photo
 * business logic of this class object
 *
 * @category   service
 * @author     javincX
 */
class Photo
{
    /* Constants
    --------------------------------------------*/
    const PRIMARY_FIELD = 'primary';

    /* Public Properties
    --------------------------------------------*/
    public static $required = array(
        'create' => array(
            'file_id'));

    /* Protected Properties
    --------------------------------------------*/
    /* Private Properties
    --------------------------------------------*/
    /* Public Methods
    --------------------------------------------*/
    public static function find($options)
    {
        // get current users photo
        $user = Auth::getUser();
        $options['filters']['user_id'] = $user['id'];

        return self::baseFind($options);
    }

    public static function baseFind($options = array())
    {
        $options['fields'] = array('id', 'photo_id');
        $options['relate'][] = 'photo';

        // get photo only
        $result = U::find($options);
        foreach($result as $key => $value) {
            // check if photo is deleted
            if(empty($value['photo'])) {
                unset($result[$key]);
                continue;
            }

            $value['photo']['id'] = $value['id'];
            $result[$key] = $value['photo'];
        }

        // fix relation to file
        // this cause is by UserPhoto Resource
        // is not related directly to File Resource
        // so we use the great Resource::relator
        return Resource::relator($result, array('file'), $options);
    }

    public static function get($options)
    {
        return current(self::find($options));
    }

    public static function create($payload)
    {
        $user = Auth::getUser();
        $p = P::create($payload);
        U::create(array(
            'user_id' => $user['id'],
            'photo_id' => $p['id']));

        return $p;
    }

    public static function update($payload, $id)
    {
        // check if exists user current user
        $p = self::getUserPhotoById($id);
        if(empty($p)) {
            return Helper::error('USER_PHOTO_NOT_FOUND',
                'user photo not exists');
        }

        return P::update($payload, $p['photo_id']);
    }

    public static function remove($id)
    {
        // check if exists user current user
        $p = self::getUserPhotoById($id);
        if(empty($p)) {
            return Helper::error('USER_PHOTO_NOT_FOUND',
                'user photo not exists');
        }

        U::remove($id);
        return P::remove($p['photo_id']);
    }

    public static function setPrimaryPhoto($id)
    {
        // check id
        if(empty($id)) {
            return Helper::error('USER_PHOTO_PRIMARY_REQUIRED',
                'id must set on the endpoint');
        }

        // NOTE this is bad query
        // id just use it for quick use
        // set all user's photos primary to 0
        foreach(self::find() as $photo) {
            // search for primary is 1
            if((bool) $photo[self::PRIMARY_FIELD] == true) {
                self::update(array(self::PRIMARY_FIELD => 0), $photo['id']);
                continue;
            }
        }

        // set primary
        return self::update(array(self::PRIMARY_FIELD => 1), $id);
    }

    public static function getPrimaryPhoto($options)
    {
        // NOTE this is bad query
        // id just use it for quick use
        foreach(self::find($options) as $photo) {
            // search for primary is 1
            if((bool) $photo[self::PRIMARY_FIELD] == true) {
                return $photo;
            }
        }

        return [];
    }

    public static function getUserPhotoById($id)
    {
        $user = Auth::getUser();
        $options = array('filters' => array(
            'user_id' => $user['id'],
            'id' => $id));

        return U::get($options);
    }

    /* Protected Methods
    --------------------------------------------*/
    /* Private Methods
    --------------------------------------------*/
}
