<?php //-->

namespace Api\Page\Password;

use Modules\Helper;
use Services\User\Password as P;

class Reset extends \Page
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
        $method = Helper::getRequestMethod();
        if($method == 'POST') {
            return P::requestReset(Helper::getJSON()['email']);
        } else if($method == 'GET') {
            // expecting a segment
            return P::confirmResetHash(Helper::getSegment(0));
        } else if($method == 'PATCH') {
            return P::updateByHash(Helper::getSegment(0), Helper::getJSON());
        }

        return Helper::error('METHOD_NOT_ALLOWED', 'method not allowed');
    }

    /* Protected Methods
    --------------------------------------------*/
    /* Private Methods
    --------------------------------------------*/
}
