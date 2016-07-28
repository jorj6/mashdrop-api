<?php //-->

namespace Api\Page\Paypal;

use Services\Log;

class Notify extends \Page
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
        // log ipn for now
        return Log::create(array(
            'type' => 'NOTIF',
            'name' => 'PAYPAL_IPN',
            'description' => 'paypal notification'
        ));
    }

    /* Protected Methods
    --------------------------------------------*/
    /* Private Methods
    --------------------------------------------*/
}
