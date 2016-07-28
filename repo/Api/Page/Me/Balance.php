<?php //-->

namespace Api\Page\Me;

use Modules\Rest;
use Modules\Helper;
use Services\User\Balance as B;

class Balance extends \Page
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
            if($cache = Helper::getCache()) {
                return $cache;
            }

            // call to api
            $result = B::get(Helper::getParam());
            // 500 seconds
            Helper::setCache($result, 500);

            return $result;
        }

        return Helper::error('METHOD_NOT_ALLOWED', 'method not allowed');
    }

    /* Protected Methods
    --------------------------------------------*/
    /* Private Methods
    --------------------------------------------*/
}
