<?php //-->

namespace Services\Campaign;

use Modules\Auth;
use Modules\Helper;
use Modules\Resource;
use Services\Setting;
use Services\Campaign;
use Services\File;
use Resources\CampaignPost as P;

/**
 * Service Campaign Post
 * business logic of this class object
 *
 * @category   service
 * @author     javincX
 */
class Post
{
    /* Constants
    --------------------------------------------*/
    const URL_PREFIX = 'v';

    /* Public Properties
    --------------------------------------------*/
    public static $required = array(
        'create' => array(
            'campaign_id'));

    /* Protected Properties
    --------------------------------------------*/
    /* Private Properties
    --------------------------------------------*/
    /* Public Methods
    --------------------------------------------*/
    public static function __callStatic($name, $args)
    {
        return P::$name(current($args), end($args));
    }

    public static function find($options)
    {
        // get result
        $result = P::find($options);

        // get settings
        $setting = Setting::getPublicSetting()['app'];
        foreach($result as $key => $post) {
            // append visit url
            $result[$key]['url'] = implode('/',
                [Helper::getSetting('url_root'), self::URL_PREFIX, $post['hash']]);

            // get file on campaign
            if(isset($post['campaign']) && !empty($post['campaign'])
            && in_array('campaign', $options['relate'])
            && in_array('file', $options['relate'])) {
                $result[$key]['campaign']['file']
                    = File::get($result[$key]['campaign']['file_id']);

                unset($result[$key]['campaign']['file_id']);
            }

            // add meta
            $result[$key]['meta'] = self::getMeta($post);
        }

        return $result;
    }

    public static function get($options)
    {
        return current(self::find($options));
    }

    public static function create($payload)
    {
        // require current user
        $user = Auth::getUser();
        if(empty($user)) {
            return Helper::error('POST_USER_REQUIRED',
                'user is required in posting a campaign');
        }

        $payload['user_id'] = $user['id'];

        return self::baseCreate($payload);
    }

    public static function findByType($type, $options = array())
    {
        // check type its pay-per-click by default
        switch ($type) {
        case Campaign::CAROUSEL_TYPE:
            $options['filters']['target'] = $type;
            break;
        default:
            $options['filters'][] = array('(target IS NULL OR target != %s)',
                Campaign::CAROUSEL_TYPE);
            break;
        }

        return self::find($options);
    }

    public static function baseCreate($payload)
    {
        $filters = array(
            'user_id' => $payload['user_id'],
            'campaign_id' => $payload['campaign_id']);

        // check duplicates
        // if not exists create one
        if($post = self::get(array('filters' => $filters))) {
            // update timestamp
            self::update($filters, $post['id']);

            return $post;
        }

        $payload['hash'] = self::genHash($payload['user_id'],
            $payload['campaign_id']);

        $data = P::create($payload);

        // append url
        $data['url'] = implode('/',
            [Helper::getSetting('url_root'), self::URL_PREFIX, $data['hash']]);

        return $data;
    }

    public static function genHash($key1, $key2)
    {
        return sha1($key1 . $key2 . microtime());
    }

    /* Protected Methods
    --------------------------------------------*/
    /* Private Methods
    --------------------------------------------*/
    private static function getMeta($post)
    {
        $meta = [];
        $totalVisit = Visit::getTotalByPostId($post['id']);

        // append total visit on the post
        $meta['total_visit'] = $totalVisit;

        // net click cost is the basis of mutitplier
        // cost now will be recorded when campaign is creating
        // a campaign base on their types
        // we will get the campaign net click cost on the campaign
        $campaignId = isset($post['campaign']) ? $post['campaign']['id']
            : $post['campaign_id'];

        $multiplier = Campaign::getById($campaignId)['meta']['net_click_cost'];

        // if multiplier is zero get the default net click cost
        // on setting because net click cost should not be null
        if($multiplier == 0) {
            // and less the cost percentage
            // this will return the default
            $multiplier = Setting::getNetClickCost();
        }

        // add revenue base on net click cost
        $meta['net_click_cost'] = $multiplier;
        $meta['revenue'] = $multiplier * $totalVisit;

        return $meta;
    }
}
