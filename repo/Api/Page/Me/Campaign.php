<?php //-->

namespace Api\Page\Me;

use Modules\Rest;
use Modules\Helper;
use Services\User\Campaign as C;

class Campaign extends \Page
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
        if(Helper::getRequestMethod() == 'GET') {
            if($cache = Helper::getCache()) {
                return $cache;
            }

            // call to api
            $result = Rest::resource(new C(), true);
            // 500 seconds
            Helper::setCache($result, 300);

            return $result;
        }

        return Helper::error('METHOD_NOT_ALLOWED', 'method not allowed');
    }

    /* Protected Methods
    --------------------------------------------*/
    /* Private Methods
    --------------------------------------------*/
}
