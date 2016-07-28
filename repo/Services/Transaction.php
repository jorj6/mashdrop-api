<?php //-->

namespace Services;

use Modules\Helper;
use Resources\Transaction as T;

/**
 * Service Transaction
 * business logic of this class object
 *
 * @category   service
 * @author     javincX
 */
class Transaction
{
    /* Constants
    --------------------------------------------*/
    const CHARGE_TYPE = 'charge';
    const REFUND_TYPE = 'refund';
    const PAYOUT_TYPE = 'payout';

    /* Public Properties
    --------------------------------------------*/
    public static $required = array(
        'create' => array(
            'type',
            'user_id',
            'provider',
            'ref',
            'amount'));

    public static $types = array(
        'payout',
        'refund',
        'charge');

    /* Protected Properties
    --------------------------------------------*/
    /* Private Properties
    --------------------------------------------*/
    /* Public Methods
    --------------------------------------------*/
    public static function __callStatic($name, $args)
    {
        return T::$name(current($args), end($args));
    }

    public static function create($payload)
    {
        // check if valid type
        if(!self::checkType($payload['type'])) {
            return Helper::error('TRANSACTION_INVALID_TYPE',
                'invalid type provided');
        }

        // check user in case user not found
        if(empty(User::getClientById($payload['user_id']))) {
            return Helper::error('TRANSACTION_USER_NOT_FOUND',
                'user not found');
        }

        // create payout
        return T::create($payload);
    }

    /* Protected Methods
    --------------------------------------------*/
    /* Private Methods
    --------------------------------------------*/
    private static function checkType($type)
    {
        return in_array($type, self::$types);
    }
}
