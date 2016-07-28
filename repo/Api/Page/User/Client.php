<?php //-->

namespace Api\Page\User;

use Modules\Helper;
use Services\User as U;

class Client extends \Page
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
        // restrict the username not to be change
        if(Helper::getRequestMethod() == 'GET') {
            // get from cache
            if($cache = Helper::getCache()) {
                return $cache;
            }

            $result = U::findByType('client', Helper::getParam());
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
