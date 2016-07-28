<?php //-->

namespace Services;

use Modules\Auth;
use Modules\Helper;
use Resources\Log as L;

/**
 * Service Log
 * business logic of this class object
 *
 * @category   service
 * @author     javincX
 */
class Log
{
    /* Constants
    --------------------------------------------*/
    /* Public Properties
    --------------------------------------------*/
    public static $required = array(
        'create' => array(
            'name',
            'description'));

    /* Protected Properties
    --------------------------------------------*/
    /* Private Properties
    --------------------------------------------*/
    /* Public Methods
    --------------------------------------------*/
    public static function __callStatic($name, $args)
    {
        return L::$name(current($args), end($args));
    }

    public static function create($payload)
    {
        // kill if calling itself
        // this solves the paradox
        $stackIndex = 0;
        if(isset($payload['description']['debug_stack'][$stackIndex])) {
            $method = end(explode('\\',
                $payload['description']['debug_stack'][$stackIndex]));

            // something went wrong
            // NOTE this is a hotfix
            if($method == '{') {
                return;
            }
        }

        // require current user
        $userId = null;
        if($user = Auth::getUser()) {
            $userId = $user['id'];
        }

        $payload['user_id'] = $userId;
        $payload['description'] = json_encode($payload['description']);
        $payload['raw_request'] = json_encode(array(
            'segment' => Helper::getSegment(),
            'param' => Helper::getParam(),
            'json' => Helper::getJson(null, false),
        ));

        return L::create($payload);
    }

    /* Protected Methods
    --------------------------------------------*/
    /* Private Methods
    --------------------------------------------*/
}
