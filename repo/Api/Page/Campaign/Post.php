<?php //-->

namespace Api\Page\Campaign;

use Modules\Helper;
use Modules\Rest;
use Services\Campaign\Post as P;

class Post extends \Page
{
    /* Constants
    --------------------------------------------*/
    /* Public Properties
    --------------------------------------------*/
    /* Protected Properties
    --------------------------------------------*/
    /* Public Methods
    --------------------------------------------*/
    public function getVariables()
    {
        $method = Helper::getRequestMethod();
        if($method == 'DELETE') {
            return Helper::error('METHOD_NOT_ALLOWED', 'method not allowed');
        }

        // handle campaign post types
        $type = Helper::getSegment(0);
        if(in_array($type, ['pay-per-click', 'carousel-space'])) {
            if($method == 'GET') {
                // get from cache
                if(empty($cache = Helper::getCache())) {
                    $result = P::findByType($type, Helper::getParam());
                    Helper::setCache($result);

                    return $result;
                }

                return $cache;
            }

            return Helper::error('METHOD_NOT_ALLOWED', 'method not allowed');
        }

        // get from cache
        if($method == 'GET' && $cache = Helper::getCache()) {
            return $cache;
        }

        // call to api
        $result = Rest::resource(new P(), true);
        Helper::setCache($result);

        return $result;
    }

    /* Protected Methods
    --------------------------------------------*/
    /* Private Methods
    --------------------------------------------*/
}
