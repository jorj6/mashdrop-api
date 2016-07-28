<?php //-->

namespace Api\Page;

use Modules\Rest;
use Modules\Helper;
use Services\Campaign\Carousel as C;

class Carousel extends \Page
{
    /* Constants
    --------------------------------------------*/
    /* Public Properties
    --------------------------------------------*/
    public $auth = false;

    /* Protected Properties
    --------------------------------------------*/
    /* Public Methods
    --------------------------------------------*/
    public function getVariables()
    {
        $userId = Helper::getSegment(0);
        $photoId = Helper::getSegment(1);
        if(Helper::getRequestMethod() == 'GET' && $userId) {
            // if with photo id it means in view mode
            // it will add the carousel space campaign
            if($photoId) {
                if($cache = Helper::getCache()) {
                    return $cache;
                }

                // call to api
                $result = C::getPhoto($userId, $photoId);
                // 500 seconds
                Helper::setCache($result);

                return $result;
            }

            if($cache = Helper::getCache()) {
                return $cache;
            }

            // call to api
            $result = C::findUserPhoto($userId);
            // 500 seconds
            Helper::setCache($result);

            return $result;
        }

        return Helper::error('METHOD_NOT_ALLOWED', 'method not allowed');
    }

    /* Protected Methods
    --------------------------------------------*/
    /* Private Methods
    --------------------------------------------*/
}
