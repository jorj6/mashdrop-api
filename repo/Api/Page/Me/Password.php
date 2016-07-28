<?php //-->

namespace Api\Page\Me;

use Modules\Rest;
use Modules\Helper;
use Services\User\Password as P;

class Password extends \Page
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
        if(Helper::getRequestMethod() == 'PATCH') {
            return P::change(Helper::getJSON());
        }

        return Helper::error('METHOD_NOT_ALLOWED', 'method not allowed');
    }

    /* Protected Methods
    --------------------------------------------*/
    /* Private Methods
    --------------------------------------------*/
}
