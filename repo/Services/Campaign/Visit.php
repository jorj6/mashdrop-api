<?php //-->

namespace Services\Campaign;

use Resources\CampaignVisit;
use Resources\CampaignPost;
use Services\Campaign;
use Modules\Helper;

/**
 * Service Category Visit
 * business logic of this class object
 *
 * @category   service
 * @author     javincX
 */
class Visit
{
    /* Constants
    --------------------------------------------*/
    const COOKIE_KEY = 'mashdrop_cookie';

    /* Public Properties
    --------------------------------------------*/
    /* Protected Properties
    --------------------------------------------*/
    /* Private Properties
    --------------------------------------------*/
    /* Public Methods
    --------------------------------------------*/
    public static function __callStatic($name, $args)
    {
        return CampaignVisit::$name(current($args), end($args));
    }

    public static function getByPostId($id, $options = array())
    {
        $options['filters']['campaign_post_id'] = $id;

        return self::getFiltered($options);
    }

    public static function getFiltered($options) {
        // get valid clicks

        // exclude unknown device_type and group by cookie
        // NOTE cookie is uniquely generated per visitor
        $options['filters'][] = array('device_type != %s', 'unknown');

        return self::find($options);
    }

    public static function getTotalByPostId($id)
    {
        // NOTE the evaluation of the visits aka clicks
        // is happening here. this will check the uniqueness
        // of the visitor cookie (created when you click a
        // campaign)

        return count(self::getByPostId($id, array(
            'filters' => array(
                'device_platform!' => 'unknown',
                'is_free' => 0),
            'fields' => array('DISTINCT(cookie)'))));
    }

    public static function record($hash)
    {
        $hash = trim($hash);

        // check required
        if(empty($hash)) {
            return false;
        }

        $post = CampaignPost::get(array(
            'relate' => array('campaign'),
            'filters' => array('hash' => $hash)));

        // check if hash exist on posted campaign
        if(empty($post)) {
            return false;
        }

        $data = array(
            'campaign_post_id' => $post['id'],
            'host' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
            'session_id' => $_COOKIE['PHPSESSID'],
            'ip' => Helper::getClientIp());

        // some visitor info
        // NOTE get_browser() is not activated by default
        // please install if this doesnt work
        $browser = get_browser(null, true);
        $platform = $browser['platform'];
        // check platform arch if windows
        if($browser['win32']) {
            $platform = $platform . ' 32';
        } else if($browser['win64']) {
            $platform = $platform .' 64';
        }

        $data['browser_name'] = $browser['browser'];
        $data['browser_version'] = $browser['version'];
        $data['browser_maker'] = $browser['browser_maker'];
        $data['browser_comment'] = $browser['comment'];
        $data['browser_name_pattern'] = $browser['browser_name_pattern'];
        $data['device_platform'] = $platform;
        $data['device_type'] = $browser['device_type'];
        $data['device_pointing_method'] = $browser['device_pointing_method'];
        $data['is_mobile'] = $browser['ismobiledevice'];
        $data['is_tablet'] = $browser['istabled'];

        // plant visitor id
        if(!isset($_COOKIE[self::COOKIE_KEY])) {
            $time = time() + (10 * 365 * 24 * 60 * 60);
            $id = sha1(serialize($data) + microtime());
            setcookie(self::COOKIE_KEY, $id, $time);
        }

        $data['cookie'] = empty($_COOKIE[self::COOKIE_KEY]) ? $id : $_COOKIE[self::COOKIE_KEY];

        // check campaign if depleted with the status = done
        // this means the all new clicks are free
        if($post['campaign']['status'] != Campaign::STATUS_LIVE) {
            $data['is_free'] = true;
        }

        // record visit
        // if no source might be somewhere
        if(empty(self::create($data))) {
            return false;
        }

        // check click credits
        self::updateCampainStatus($post['campaign']);

        // return redirect
        return $post;
    }

    /* Protected Methods
    --------------------------------------------*/
    /* Private Methods
    --------------------------------------------*/
    private static function updateCampainStatus($campaign)
    {
        // compare clicks remaining
        // if clicks remaining reached 0
        // set the campaign status to done
        $totalVisit = 0;
        foreach(Post::find(array(
            'fields' => array('id'),
            'filters' => array(
                'campaign_id' => $campaign['id']))) as $p) {
            // getting raw visits counts
            $totalVisit += Visit::getTotalByPostId($p['id']);
        }

        // do nothing if not yet reached
        if($totalVisit < $campaign['max_clicks']) {
            return;
        }

        // update
        Campaign::update(array(
            'status' => Campaign::STATUS_DONE
        ), $campaign['id']);

    }

}
