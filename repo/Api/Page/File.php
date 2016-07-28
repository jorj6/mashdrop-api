<?php //-->

namespace Api\Page;

use Modules\Helper;
use Modules\Rest;
use Services\File as F;

class File extends \Page
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
            return Rest::resource(new F(), true);
        }

        return Helper::error('METHOD_NOT_ALLOWED', 'method not allowed');
    }

    /* Protected Methods
    --------------------------------------------*/
    /* Private Methods
    --------------------------------------------*/
}
