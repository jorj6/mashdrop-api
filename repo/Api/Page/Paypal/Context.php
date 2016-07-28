<?php //-->

namespace Api\Page\Paypal;

use Modules\Paypal;
use Modules\Helper;

class Context extends \Page
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
        $data = Helper::getPayload();
        $result = Paypal::setEC($data);

        Helper::redirect($result['redirect']);
    }

    /* Protected Methods
    --------------------------------------------*/
    /* Private Methods
    --------------------------------------------*/
}
