<?php //-->

namespace Services;

use Resources\Campaign as C;
use Resources\Audience;
use Resources\Category;

use Services\Transaction;
use Services\Campaign\Post;
use Services\Campaign\Projection;
use Services\Campaign\Visit;

use Modules\Auth;
use Modules\Helper;
use Modules\Stripe;
use Modules\Paypal;

/**
 * Service Audience
 * business logic of this class object
 *
 * @category   service
 * @author     javincX
 */
class Campaign
{
    /* Constants
    --------------------------------------------*/
    const CLICK_TYPE = 'pay-per-click';
    const CAROUSEL_TYPE = 'carousel-space';

    const STATUS_LIVE = 'live';
    const STATUS_DONE = 'done';
    const STATUS_PENDING = 'pending';

    const PAYMENT_STRIPE = 'stripe';
    const PAYMENT_PAYPAL = 'paypal';

    /* Public Properties
    --------------------------------------------*/
    public static $required = array(
        'create' => array(
            'audience',
            'category_id',
            'file_id',
            'type',
            'title',
            'link',
            'payment'));

    public static $redirect = null;

    /* Protected Properties
    --------------------------------------------*/
    /* Private Properties
    --------------------------------------------*/
    /* Public Methods
    --------------------------------------------*/
    public static function __callStatic($name, $args)
    {
        return C::$name(current($args), end($args));
    }

    public static function find($options)
    {
        // modify result
        $result = C::find($options);
        foreach($result as $key => $value) {
            $result[$key] = self::resultModifier($value);
        }

        return $result;
    }

    public static function get($options)
    {
        $result = C::get($options);
        $result = self::resultModifier($result);

        return $result;
    }

    public static function create($payload)
    {
        $setting = Setting::getPublicSetting()['app'];

        // require current user
        $user = Auth::getUser();
        if(empty($user)) {
            return Helper::error('CAMPAIGN_USER_REQUIRED',
                'user is required in creating a campaign');
        }

        // add current user
        $payload['user_id'] = $user['id'];

        // validate and calucalte budget
        list($payload, $errorMsg) = self::calculateBudget($payload, $setting);
        if($errorMsg) {
            return Helper::error('CAMPAIGN_BUDGET_ERROR', $errorMsg);
        }

        // check link
        if(Helper::validateUrl($payload['link']) === false) {
            return Helper::error('CAMPAIGN_LINK_INVALID',
                'link is not valid');
        }

        // check file
        if(!File::get(array(
            'filters' => array(
                'id' => $payload['file_id']),
            'fields' => array('id')))) {
            return Helper::error('CAMPAIGN_FILE_ID_INVALID',
                'file_id not exists');
        }

        // check category
        if(!Category::get(array(
            'filters' => array(
                'id' => $payload['category_id']),
            'fields' => array('id')))) {
            return Helper::error('CAMPAIGN_CATEGORY_ID_INVALID',
                'category_id not exists');
        }

        // check audience
        if($field = self::checkAudienceInput($payload['audience'])) {
            return Helper::error('CAMPAIGN_AUDIENCE_FIELD_REQUIRED',
                'audience.' . $field . ' is required in creating a campaign');
        }

        // create audience
        $audience = Audience::create($payload['audience']);

        // process charge and transaction
        $transaction = self::processPayment($payload);
        if(isset($transaction['error'])) {
            return $transaction;
        }

        // transaction error
        if(isset($transaction['error'])) {
            return $transaction['error'];
        }

        // get audience id
        $payload['audience_id'] = $audience['id'];
        // add transaction
        $payload['transaction_id'] = $transaction['id'];

        // NOTE for paypal payment. set status to pending
        // because campaign is created first
        if(isset($payload['payment']['type'])
        && $payload['payment']['type'] == self::PAYMENT_PAYPAL) {
            $payload['status'] = self::STATUS_PENDING;
        }

        // remove not needed
        unset($payload['audience'], $payload['payment']);

        // add cost percentage
        $payload['cost_percentage'] = (float) $setting[Setting::COST_PERCENTAGE];

        // check payload requirements
        $campaign = C::create($payload);

        // set paypal redirect
        $campaign['redirect'] = self::$redirect;

        return $campaign;
    }

    public static function findByType($type, $options = array())
    {
        $options['filters']['type'] = $type;

        return self::find($options);
    }

    public static function getById($id, $options = array())
    {
        $options['filters']['id'] = $id;

        return self::get($options);
    }

    /* Protected Methods
    --------------------------------------------*/
    /* Private Methods
    --------------------------------------------*/
    private static function calculateBudget($payload, $setting)
    {
        // get app setting
        $error = false;
        $requiredFields = ['max_clicks', 'click_cost'];

        switch ($payload['type']) {
        case self::CLICK_TYPE:
            // max clicks and click cost required
            if($field = Helper::getMissingFields($payload, $requiredFields)) {
                Helper::panic('CAMPAIGN_BUDGET_FIELDS_REQUIRED',
                    $field . ' required, empty given');
            }

            // check if setting is greater that minimum
            $maxClicks = (int) $payload['max_clicks'];
            $clickCost = (float) $payload['click_cost'];
            if((int) $setting[Setting::MIN_CLICKS] > $maxClicks
            || (float) $setting[Setting::MIN_CLICK_COST] > $clickCost) {
                $error = 'max_clicks or click_cost did not meet the minimum of ' .
                    $setting[Setting::MIN_CLICKS] . ' max_clicks or ' .
                    $setting[Setting::MIN_CLICK_COST] . ' click_cost';
            }

            // just multiply it
            $payload['budget'] = (float) $maxClicks * $clickCost;

            break;
        case self::CAROUSEL_TYPE:
            // max clicks and click should not be set
            if(isset($payload['max_clicks']) || isset($payload['click_cost'])) {
                $error = 'max_clicks or click_cost should not be set';
            }

            // get flat fee on setting
            $payload['budget'] = $setting[Setting::FLAT_FEE];
            $payload['click_cost'] = $setting[Setting::MIN_CLICK_COST];

            break;
        default;
            // unknown type
            $error = 'unknown campaign type of \'' . $payload['type'] . '\'';
        }

        return [$payload, $error];
    }

    private static function stripeCharge($data)
    {
        // check required
        if(Helper::getMissingFields($data['payment'], ['token'])) {
            return Helper::error('CAMPAIGN_PAYMENT_STRIPE_FIELDS_REQUIRED',
                'payment token required, empty given');
        }

        // stripe doesnt accept float values
        // so we move decimal point to hundredths
        $amount = ((float) $data['budget']) * 100;

        // stripe payment
        // use title for description & stripe_token_id for token
        $charge = Stripe::createCharge($data['payment']['token'], array(
            'amount' => $amount,
            'description' => $data['title']));

        // check result
        if($charge == false) {
            return Helper::error('CAMPAIGN_PAYMENT_STRIPE_ERROR',
                'something went wrong on stripe charge');
        }

        return $charge;
    }

    // NOTE this requires token and PayerID
    // from Paypal Express Checkout
    // this will only do the charging
    private static function paypalCharge($data)
    {
        // check required
        if($fields = Helper::getMissingFields($data['payment'], ['token', 'payer_id'])) {
            return Helper::error('CAMPAIGN_PAYMENT_PAYPAL_FIELDS_REQUIRED',
                'payment ' . $fields . ' required, empty given');
        }

        // set express checkout
        $charge = Paypal::doEC($data['payment']['token'], $data['payment']['payer_id']);

        // return as formatted on stripe
        return array('id' => current($charge)['token']);
    }

    // paypal sey charge only
    private static function paypalSetPayment($data)
    {
        $charge = Paypal::setEC(array(
            'name' => $data['title'],
            'amount' => (float) $data['budget']));

        // set redirect
        self::$redirect = $charge['redirect'];

        return array('id' => $charge['token']);
    }

    private static function processPayment($data)
    {
        // check payment type
        if(!isset($data['payment']['type'])) {
            return Helper::error('CAMPAIGN_PAYMENT_TYPE_NOT_SET',
                'payment type not exist');
        }

        $transaction = array(
            'type' => Transaction::CHARGE_TYPE,
            'provider' => $data['payment']['type'],
            'user_id' => $data['user_id'],
            'amount' => $data['budget']);

        // evaluate
        $ref = null;
        switch (strtolower($data['payment']['type'])) {
        case self::PAYMENT_STRIPE:
            $charge = self::stripeCharge($data);
            break;
        case self::PAYMENT_PAYPAL:
            $charge = self::paypalSetPayment($data);
            break;
        default:
            return Helper::error('CAMPAIGN_PAYMENT_TYPE_NOT_SET',
                'payment type not exist');
        }

        // check error
        if(isset($charge['error'])) {
            return $charge;
        }

        // set reference id for future
        $ref = $charge['id'];

        // set transaction ref
        $transaction['ref'] = $ref;

        // record transaction
        return Transaction::create($transaction);
    }

    private static function checkAudienceInput($data)
    {
        return Helper::getMissingFields(
            $data, array_values(Audience::$required['create']));
    }

    private static function resultModifier($row)
    {
        // provide meta info
        $row['meta'] = self::getMeta($row);

        return $row;
    }

    private static function getMeta($row)
    {
        $data = array();
        $id = $row['id'];

        // get net per click
        // this data is used for publishers credit
        // less by the cost percentage setting
        $data['net_click_cost'] = Setting::getNetClickCost($row['click_cost'],
            $row['cost_percentage']);

        // getting posts
        $post = Post::find(array(
            'fields' => array('id'),
            'filters' => array('campaign_id' => $id)));
        // $data['post'] = $post;
        $data['total_post'] = count($post);

        // getting post visits to get total visits
        // NOTE you can use this visits as clicks
        $totalVisit = 0;
        foreach($post as $p) {
            // getting raw visits counts
            $totalVisit += Visit::getTotalByPostId($p['id']);
        }

        $data['total_visit'] = $totalVisit;

        // getting projections
        $projection = Projection::find(array(
            'fields' => array('id'),
            'filters' => array('campaign_id' => $id)));
        // $data['post'] = $post;
        $data['total_projection'] = count($projection);

        return $data;
    }
}
