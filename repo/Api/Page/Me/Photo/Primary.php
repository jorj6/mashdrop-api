<?php //-->

namespace Api\Page\Me\Photo;

use Modules\Rest;
use Modules\Helper;
use Services\User\Photo as P;

class Primary extends \Page
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
        $method = Helper::getRequestMethod();
        if($method == 'GET') {
            return P::getPrimaryPhoto(Helper::getParam());
        } else if($method == 'PATCH') {
            return P::setPrimaryPhoto(Helper::getSegment(0));
        }

        return Helper::error('METHOD_NOT_ALLOWED', 'method not allowed');
    }

    /* Protected Methods
    --------------------------------------------*/
    /* Private Methods
    --------------------------------------------*/
}
