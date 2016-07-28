<?php //-->

namespace Api\Page;

use Modules\Rest;
use Modules\Helper;
use Services\Permission;
use Services\Campaign as C;

class Campaign extends \Page
{
    /* Constants
    --------------------------------------------*/
    /* Public Properties
    --------------------------------------------*/
    public static $permissions = array(
        // 'GET' => Permission::CAMPAIGN_VIEW,
        // 'POST' => Permission::CAMPAIGN_CREATE,
        'PATCH' => Permission::CAMPAIGN_UPDATE,
        'DELETE' => Permission::CAMPAIGN_REMOVE,
    );

    /* Protected Properties
    --------------------------------------------*/
    /* Public Methods
    --------------------------------------------*/
    public function getVariables()
    {
        // handle campaign types
        $method = Helper::getRequestMethod();
        $type = Helper::getSegment(0);
        if(in_array($type, ['pay-per-click', 'carousel-space'])) {
            if($method == 'GET') {
                // get from cache
                // if(empty($cache = Helper::getCache())) {
                    $result = C::findByType($type, Helper::getParam());
                    Helper::setCache($result);

                    return $result;
                // }

                return $cache;
            }

            return Helper::error('METHOD_NOT_ALLOWED', 'method not allowed');
        }

        // get from cache
        // if($method == 'GET' && $cache = Helper::getCache()) {
            // return $cache;
        // }

        // call to api
        $result = Rest::resource(new C(), true);
        Helper::setCache($result);

        return $result;
    }

    /* Protected Methods
    --------------------------------------------*/
    /* Private Methods
    --------------------------------------------*/
}
