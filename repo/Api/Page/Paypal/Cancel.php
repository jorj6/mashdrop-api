<?php //-->

namespace Api\Page\Paypal;

use Modules\Helper;
use Modules\Paypal;

class Cancel extends \Page
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
        Helper::redirect(Helper::getSetting('app_root') . '/#/campaign/create');
    }

    /* Protected Methods
    --------------------------------------------*/
    /* Private Methods
    --------------------------------------------*/
}
