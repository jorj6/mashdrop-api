<?php //-->

namespace Api\Page;

use Modules\Rest;
use Modules\Helper;
use Services\User;

class Register extends \Page
{
    /* Constants
    --------------------------------------------*/
    /* Public Properties
    --------------------------------------------*/
    public $auth = false;

    /* Protected Properties
    --------------------------------------------*/
    /* Public Methods
    --------------------------------------------*/
    public function getVariables()
    {
        // public access for post
        if(Helper::getRequestMethod() == 'POST') {
            return Rest::resource(new User(), true);
        }

        return Helper::error('METHOD_NOT_ALLOWED', 'method not allowed');
    }

    /* Protected Methods
    --------------------------------------------*/
    /* Private Methods
    --------------------------------------------*/
}
