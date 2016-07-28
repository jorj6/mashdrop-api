<?php //-->

namespace Api\Page;

use Modules\Helper;
use Services\Me as M;

class Me extends \Page
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
            return M::get();
        }

        return Helper::error('METHOD_NOT_ALLOWED', 'method not allowed');
    }

    /* Protected Methods
    --------------------------------------------*/
    /* Private Methods
    --------------------------------------------*/
}
