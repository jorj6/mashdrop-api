<?php //-->

namespace Api\Page;

use Modules\Rest;
use Modules\Helper;
use Services\Balance as B;

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
            return B::find(Helper::getParam());
        }

        return Helper::error('METHOD_NOT_ALLOWED', 'method not allowed');
    }

    /* Protected Methods
    --------------------------------------------*/
    /* Private Methods
    --------------------------------------------*/
}
