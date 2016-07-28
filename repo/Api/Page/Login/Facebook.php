<?php //-->

namespace Api\Page\Login;

use Modules\Helper;
use Services\User\Login as L;

class Facebook extends \Page
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
            $payload = Helper::getJSON();

            // check required
            if($field = Helper::getMissingFields($payload, array(
                'user_id', 'token'))) {
                return Helper::error('LOGIN_FIELDS_REQUIRED',
                    $field . ' required, empty given');
            }

            return L::facebook($payload['user_id'], $payload['token']);
        }

        return Helper::error('METHOD_NOT_ALLOWED', 'method not allowed');
    }

    /* Protected Methods
    --------------------------------------------*/
    /* Private Methods
    --------------------------------------------*/
}
