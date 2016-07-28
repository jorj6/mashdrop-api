<?php //-->

namespace Api\Page\Paypal;

use Modules\Helper;
use Modules\Paypal;

use Services\Campaign;
use Services\Transaction;

class Callback extends \Page
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
        $param = Helper::getParam();
        // $data = Paypal::getEC($param['token']);
        $data = Paypal::doEC($param['token'], $param['PayerID']);

        // confirm the payment
        // use token as Transaction reference or ref
        $txn = Transaction::get(array(
            'fields' => array('id'),
            'filters' => array('ref' => $param['token'])));

        // if exist set campagin to live
        if($txn) {
            $campaign = Campaign::update(array(
                'status' => Campaign::STATUS_LIVE
            ), array(
                'transaction_id' => $txn['id']
            ));
        }

        // Helper::debug($data, 1);

        Helper::redirect(Helper::getSetting('app_root')
            . '/#/campaign/list');
    }

    /* Protected Methods
    --------------------------------------------*/
    /* Private Methods
    --------------------------------------------*/
}
