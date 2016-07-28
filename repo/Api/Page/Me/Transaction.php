<?php //-->

namespace Api\Page\Me;

use Modules\Rest;
use Modules\Helper;
use Services\User\Transaction as T;

class Transaction extends \Page
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
            return Rest::resource(new T(), true);
        }

        return Helper::error('METHOD_NOT_ALLOWED', 'method not allowed');
    }

    /* Protected Methods
    --------------------------------------------*/
    /* Private Methods
    --------------------------------------------*/
}
