<?php //-->

namespace Services;

use Modules\Helper;
use Resources\Setting as S;
use Services\User\Setting as U;

/**
 * Service Setting
 * business logic of this class object
 *
 * @category   service
 * @author     javincX
 */
class Setting
{
    /* Constants
    --------------------------------------------*/
    const ADMIN_ID = 1000;

    const MIN_CLICK_COST = 'min_click_cost';
    const MIN_CLICKS = 'min_clicks';
    const FLAT_FEE = 'flat_fee';
    const COST_PERCENTAGE = 'cost_percentage';

    /* Public Properties
    --------------------------------------------*/
    public static $required = array(
        'create' => array(
            'key',
            'value'));

    /* Protected Properties
    --------------------------------------------*/
    /* Private Properties
    --------------------------------------------*/
    /* Public Methods
    --------------------------------------------*/
    public static function __callStatic($name, $args)
    {
        return S::$name(current($args), end($args));
    }

    public static function getPublicSetting()
    {
        // get publishable setting only
        $settings = Helper::getSetting();

        // get app setting on database
        // admin id is refered to application
        $app = array();
        // convert it to key pair value
        foreach(U::getByUserId(self::ADMIN_ID) as $setting) {
            $app[$setting['key']] = $setting['value'];
        }

        return array(
            'app' => $app,
            'stripe' => array(
                'publishable_key' => $settings['stripe']['publishable_key']),
            'facebook' => array(
                'app_id' => $settings['facebook']['app_id'],
                'default_graph_version' =>
                    $settings['facebook']['default_graph_version']));
    }

    public static function getNetClickCost($cost = 0, $percentage = 0)
    {
        $setting = self::getPublicSetting()['app'];

        // get default when cost param not defined
        $cost = (float) $cost ? $cost : $setting[self::MIN_CLICK_COST];
        $percentage = (float) $percentage ? $percentage
            : $setting[self::COST_PERCENTAGE];

        return $cost - $cost * ($percentage / 100);
    }

    /* Protected Methods
    --------------------------------------------*/
    /* Private Methods
    --------------------------------------------*/
}
