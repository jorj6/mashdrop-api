<?php //-->

namespace Modules;

use Exception;
use PayPal as P;

 /**
  * Module Paypal Express Checkout Only
  * tool, wrapper, and helper of this class object
  *
  * @category   utility
  * @author     javincX
  */
class Paypal
{
    /* Constants
    --------------------------------------------*/
    const DEFAULT_VERSION = '104.0';
    const DEFAULT_CURRENCY = 'USD';
    const DEFAULT_PAYMENT_ACTION = 'Sale';

    const RETURN_URL = '/paypal/callback';
    const CANCEL_URL = '/paypal/cancel';
    const NOTIFY_URL = '/paypal/ipn';

    // sandbox by default
    const EC_HOST = 'https://www.sandbox.paypal.com';
    const EC_URL = '/cgi-bin/webscr?cmd=_express-checkout&token=';

    /* Public Properties
    --------------------------------------------*/
    public static $required = array(
        'set' => [
            'name',
            'amount']);

    /* Protected Properties
    --------------------------------------------*/
    /* Private Properties
    --------------------------------------------*/
    /* Public Methods
    --------------------------------------------*/
    public static function init()
    {
        list($mode, $host) = self::getMode();

        return new P\Service\PayPalAPIInterfaceServiceService(array(
            'mode' => $mode,
            'acct1.UserName' => self::setting()['user'],
            'acct1.Password' => self::setting()['pass'],
            'acct1.Signature' => self::setting()['signature']
        ));
    }

    public static function setEC($data = array())
    {
        // check required
        if($field = Helper::getMissingFields($data, self::$required['set'])) {
            Helper::panic('PAYPAL_CHARGE_FIELDS_REQUIRED',
                $field . ' required, empty given');
        }

        // get mode and host
        list($mode, $host) = self::getMode();

        // init service
        $service = self::init();

        $details = new P\EBLBaseComponents\PaymentDetailsType();
        $item = new P\EBLBaseComponents\PaymentDetailsItemType();
        $item->Name = $data['name'];
        $item->Amount = $data['amount'];
        // default quantity
        $item->Quantity = isset($data['quantity']) ? $data['quantity'] : 1;

        $details->PaymentDetailsItem[0] = $item;

        $total = new P\CoreComponentTypes\BasicAmountType();
        $total->currencyID = self::DEFAULT_CURRENCY;
        $total->value = $item->Amount * $item->Quantity;

        $details->OrderTotal = $total;
        $details->PaymentAction = self::DEFAULT_PAYMENT_ACTION;

        $reqDetails = new P\EBLBaseComponents\SetExpressCheckoutRequestDetailsType();
        $reqDetails->PaymentDetails[0] = $details;

        $reqDetails->CancelURL = Helper::getSetting()['url_root'] . self::CANCEL_URL;
        $reqDetails->ReturnURL = Helper::getSetting()['url_root'] . self::RETURN_URL;

        $reqType = new P\PayPalAPI\SetExpressCheckoutRequestType();
        $reqType->Version = self::DEFAULT_VERSION;
        $reqType->SetExpressCheckoutRequestDetails = $reqDetails;

        $req = new P\PayPalAPI\SetExpressCheckoutReq();
        $req->SetExpressCheckoutRequest = $reqType;

        // check error
        try {
            $res = $service->SetExpressCheckout($req);
            self::checkError($res);
        } catch (Exception $e) {
            Helper::panic('PAYPAL_SET_EC_EXCEPTION', $e->getMessage());
        }

        // normalize data
        $data = self::normalizeData($res);
        $data['redirect'] = $host . self::EC_URL . $res->Token;

        return $data;
    }

    public static function getEC($token, $raw = false)
    {
        $service = self::init();

        $reqDetails = new P\PayPalAPI\GetExpressCheckoutDetailsRequestType($token);
        $reqDetails->Version = '104.0';
        $req = new P\PayPalAPI\GetExpressCheckoutDetailsReq();
        $req->GetExpressCheckoutDetailsRequest = $reqDetails;

        $res = $service->GetExpressCheckoutDetails($req)
            ->GetExpressCheckoutDetailsResponseDetails;


        // check error
        if($res->Token == null) {
            Helper::panic('PAYPAL_GET_EC_EXCEPTION', 'invalid token' . $token);
        }

        // check res type
        return $raw ? $res : self::normalizeData($res);
    }

    public static function doEC($token, $payerId)
    {
        $service = self::init();

        // get first
        $details = current(self::getEC($token, true)->PaymentDetails);
        $details->NotifyURL = Helper::getSetting()['url_root'] . self::NOTIFY_URL;

        $reqDetails = new P\EBLBaseComponents\DoExpressCheckoutPaymentRequestDetailsType();
        $reqDetails->PayerID = $payerId;
        $reqDetails->Token = $token;
        $reqDetails->PaymentDetails[0] = $details;

        $type = new P\PayPalAPI\DoExpressCheckoutPaymentRequestType();
        $type->DoExpressCheckoutPaymentRequestDetails = $reqDetails;
        $type->Version = self::DEFAULT_VERSION;

        $req = new P\PayPalAPI\DoExpressCheckoutPaymentReq();
        $req->DoExpressCheckoutPaymentRequest = $type;

        // check error
        try {
            $res = $service->DoExpressCheckoutPayment($req);
            self::checkError($res);
        } catch (Exception $e) {
            Helper::panic('PAYPAL_DO_EC_EXCEPTION', $e->getMessage());
        }

        return self::normalizeData($res);
    }

    /* Protected Methods
    --------------------------------------------*/
    protected static function setting()
    {
        return Helper::getSetting('paypal');
    }

    protected static function getMode()
    {
        $mode = 'sandbox';
        $host = self::EC_HOST;
        if(self::setting()['live']) {
            // NOTE live mode
            $host = str_replace($mode . '.', '', $host);
            $mode = 'live';
        }

        return [$mode, $host];
    }

    protected static function checkError($requestObj)
    {
        $errorProperty = 'Errors';

        // check errors
        if(property_exists($requestObj, $errorProperty)
        && count($requestObj->$errorProperty) > 0) {
            // get first error
            Helper::throwError(current(
                $requestObj->$errorProperty)->LongMessage);
        }

        // good
        return null;
    }

    // normalize data
    protected static function normalizeData($o)
    {
        $data = json_decode(json_encode($o), true);

        return self::normalizeKeyCase($data);
    }

    // turn keys into lowercase
    protected static function normalizeKeyCase($result) {
        // normalize casing of key
        foreach($result as $key => $row) {
            // recusive
            if(is_array($row)) {
                $row = self::normalizeKeyCase($row);
            }

            // unsets
            unset($result[$key]);

            // set
            $result[strtolower($key)] = $row;
        }

        return $result;
    }


    /* Private Methods
    --------------------------------------------*/
}
