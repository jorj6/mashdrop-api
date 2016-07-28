<?php //-->

namespace Modules;

use Exception;
use Stripe as S;

 /**
  * Module Stripe
  * tool, wrapper, and helper of this class object
  *
  * @category   utility
  * @author     javincX
  */
class Stripe
{
    /* Constants
    --------------------------------------------*/
    const DEFAULT_CURRENCY = 'usd';

    /* Public Properties
    --------------------------------------------*/
    public static $required = array(
        'charge' => [
            'amount',
            'description']);

    /* Protected Properties
    --------------------------------------------*/
    /* Private Properties
    --------------------------------------------*/
    /* Public Methods
    --------------------------------------------*/
    public static function init()
    {
        S\Stripe::setApiKey(self::setting()['secret_key']);
    }

    public static function createCharge($token, $data = array())
    {
        // set keys
        self::init();

        // check required
        if($field = Helper::getMissingFields($data, self::$required['charge'])) {
            Helper::panic('STRIPE_CHARGE_FIELDS_REQUIRED',
                $field . ' required, empty given');
        }

        $data['source'] = $token;
        // inject current if not stated
        $data['currency'] = isset($data['currency']) ? $data['currency']
            : self::DEFAULT_CURRENCY;

        // Create the charge on Stripe's servers -
        // this will charge the user's card
        try {
            return S\Charge::create($data);
        } catch(S\Error\Card $e) {
            // The card has been declined
            $error = $e->getJsonBody();
            Helper::panic('STRIPE_CHARGE_ERROR_CARD',
                $error['error']['message']);
        } catch(Exception $e) {
            Helper::panic('STRIPE_CHARGE_EXCEPTION', $e->getMessage());
        }

        return false;
    }

    /* Protected Methods
    --------------------------------------------*/
    protected static function setting()
    {
        return Helper::getSetting('stripe');
    }

    /* Private Methods
    --------------------------------------------*/
}
