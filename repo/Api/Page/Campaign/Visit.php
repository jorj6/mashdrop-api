<?php //-->

namespace Api\Page\Campaign;

use Services\Campaign\Visit as V;
use Modules\Helper;

class Visit extends \Page
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
        // public access for get only
        if(Helper::getRequestMethod() != 'GET') {
            return Helper::error('METHOD_NOT_ALLOWED', 'method not allowed');
        }

        // record the hit
        $post = V::record(Helper::getSegment(0));
        if(empty($post)) {
            Helper::redirect(Helper::getSetting('app_root'));

            return;
        }

        Helper::redirect($post['campaign']['link']);
    }

    /* Protected Methods
    --------------------------------------------*/
    /* Private Methods
    --------------------------------------------*/
}
