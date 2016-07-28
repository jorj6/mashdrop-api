<?php //-->

namespace Services\Campaign;

use Resources\CampaignProjection as P;

/**
 * Service Campaign Projection
 * business logic of this class object
 *
 * @category   service
 * @author     javincX
 */
class Projection
{
    /* Constants
    --------------------------------------------*/
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
        return P::$name(current($args), end($args));
    }

    public static function getTotalByPhotoId($id)
    {
        return count(self::getByPhotoId($id, array(
            'fields' => array('id'))));
    }

    public static function getByPhotoId($id, $options = array())
    {
        $options['filters']['photo_id'] = $id;

        return self::find($options);
    }

    public static function record($photoId, $campaigns = array())
    {
        foreach($campaigns as $campaign) {
            self::create(array('photo_id' => $photoId, 'campaign_id' => $campaign['id']));
        }

        return true;
    }

    /* Protected Methods
    --------------------------------------------*/
    /* Private Methods
    --------------------------------------------*/
}
