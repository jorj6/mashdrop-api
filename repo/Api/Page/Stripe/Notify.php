<?php //-->

namespace Api\Page\Stripe;

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
            'name' => 'STRIPE_IPN',
            'description' => 'stripe notification'
        ));
    }

    /* Protected Methods
    --------------------------------------------*/
    /* Private Methods
    --------------------------------------------*/
}
