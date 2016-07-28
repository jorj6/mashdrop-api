<?php //-->

namespace Services\Campaign;

use Modules\Helper;
use Services\Campaign;
use Services\User;
use Services\Me;
use Services\User\Photo;

/**
 * Service Category
 * business logic of this class object
 *
 * @category   service
 * @author     javincX
 */
class Carousel
{
    /* Constants
    --------------------------------------------*/
    const MAX_CAMPAIGN = 2;

    /* Public Properties
    --------------------------------------------*/
    /* Protected Properties
    --------------------------------------------*/
    /* Private Properties
    --------------------------------------------*/
    /* Public Methods
    --------------------------------------------*/
    public static function findUserPhoto($id, $options = array())
    {
        $data = array();

        // search user id if client type
        $user = User::getClientById($id);
        // remove role id
        unset($user['role_id']);
        if(empty($user)) {
            return Helper::error('CAROUSEL_USER_NOT_FOUND',
                'user not found');
        }

        // get more info from me service
        $data['user'] = Me::get($id);

        // get user photos
        $options['filters']['user_id'] = $user['id'];
        // get files as well by default
        $options['relate'][] = 'file';

        $data['photos'] = Photo::baseFind($options);

        return $data;
    }

    public static function getPhoto($userId, $photoId)
    {
        // get campaign that will be projected
        $space = self::getCarouselSpace($userId, $photoId);

        // get user photo
        // build option
        $options = array('filters' => array('photo_id' => $photoId));
        $data = self::findUserPhoto($userId, $options);
        // catch error
        if(isset($data['error'])) {
            return $data;
        }

        $data['photo'] = current($data['photos']);
        $data['space'] = $space;
        unset($data['photos']);

        return $data;
    }

    /* Protected Methods
    --------------------------------------------*/
    /* Private Methods
    --------------------------------------------*/
    private static function getCarouselSpace($userId, $photoId)
    {
        // ready campaign that will be projected
        $options = array('relate' => array('file'));
        $campaigns = Campaign::findByType(Campaign::CAROUSEL_TYPE, $options);

        // control algorithm
        // NOTE for now use random
        shuffle($campaigns);

        // select max campaign that will be projected
        $selected = array_slice($campaigns, 0, self::MAX_CAMPAIGN);

        // normalize data
        foreach($selected as $key => $space) {
           $post = Post::baseCreate(array(
                'user_id' => $userId,
                'ref' => $photoId,
                'target' => 'carousel-space',
                'campaign_id' => $space['id']));

            $selected[$key]['url'] = $post['url'];

            unset($selected[$key]['meta']);
        }

        // record projection
        Projection::record($photoId, $selected);

        return $selected;
    }
}
