<?php //-->

namespace Api\Page\Me\Campaign;

use Modules\Rest;
use Modules\Helper;
use Services\User\Post as P;

class Post extends \Page
{
    
    /* Constants
    --------------------------------------------*/
    /* Public Properties
    --------------------------------------------*/
    // public $auth = false;

    /* Protected Properties
    --------------------------------------------*/
    /* Public Methods
    --------------------------------------------*/
    public function getVariables()
    {
        
        if(Helper::getRequestMethod() == 'GET') {
            // handle campaign post types
            $type = Helper::getSegment(0);
            if(in_array($type, ['pay-per-click', 'carousel-space'])) {
                if($cache = Helper::getCache()) {
                    return $cache;
                }

                // call to api
                $result = P::findByType($type, Helper::getParam());
                // 500 seconds
                Helper::setCache($result, 300);

                return $result;
            }
        }

        if($method == 'GET' && $cache = Helper::getCache()) {
            return $cache;
        }

        // call to api
        $result = Rest::resource(new P(), true);
        // 500 seconds
        Helper::setCache($result, 300);

        return $result;
    }

    /* Protected Methods
    --------------------------------------------*/
    /* Private Methods
    --------------------------------------------*/
}
