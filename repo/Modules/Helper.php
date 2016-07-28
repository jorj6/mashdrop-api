<?php //-->

namespace Modules;

use Exception;
use Services\Log;

/**
 * Module Helper
 * structure utility
 * tool, wrapper, and helper of this class object
 *
 * @category   utility
 * @author     javincX
 */
class Helper
{
    /* Constants
    --------------------------------------------*/
    /* Public Properties
    --------------------------------------------*/
    /* Protected Properties
    --------------------------------------------*/
    /* Public Methods
    --------------------------------------------*/
    /*
     * get POST JSON data
     *
     * @param int index
     * @return array
     */
    public static function getJson($field = null, $panic = true)
    {
        if($input = (array) json_decode(file_get_contents('php://input'))) {
            control()->registry()->set('input', $input);
        }

        $data = self::getData((array)control()->registry()['input'], $field);

        // check if invalid json
        if(empty($data) && $data !== null) {
            if($panic) {
                self::panic('HELPER_INVALID_JSON_PAYLOAD', 'invalid Json');
            }

            return;
        }

        return self::objectToArray($data);
    }

    /*
     * get GET paramters data
     *
     * @param int index
     * @return array
     */
    public static function getParam($field = null)
    {
        return self::getData(
            control()->registry()['get'],
            $field);
    }

    /*
     * get POST paramters data
     *
     * @param int index
     * @return array
     */
    public static function getPayload($field = null)
    {
        return self::getData(
            control()->registry()['post'],
            $field);
    }

    /*
     * get request URI
     *
     * @param int index
     * @return array
     */
    public static function getSegment($index = null)
    {
        return self::getData(
            control()->registry()['request']['variables'],
            $index);
    }

    /*
     * get request method wrapper
     *
     * @return string
     */
    public static function getRequestMethod()
    {

        return self::getServer()['REQUEST_METHOD'];
    }

    /*
     * get PHP SERVER data
     *
     * @return array
     */
    public static function getServer($index = null)
    {
        return self::getData(
            control()->registry()['server'],
            $index);
    }

    /*
     * get $_FILES data
     *
     * @return array
     */
    public static function getFile($field = null)
    {
        return self::getData(
            control()->registry()['files'],
            $field);
    }

    /*
     * get settings
     *
     * @return array
     */
    public static function getSetting($index = null)
    {
        return self::getData(
            control()->config('/settings'),
            $index);
    }

    /*
     * get databases
     *
     * @return array
     */
    public static function getDatabase($index = null)
    {
        return self::getData(
            control()->config('/databases'),
            $index);
    }

    /*
     * get cache
     *
     * @return array
     */
    public static function getCache()
    {
        // flagged as no cache
        $key = 'nocache';
        if(isset(Helper::getParam()[$key])) {
            control()->registry()->remove('get', 'nocache');

            return null;
        }

        $u = isset(Auth::getUser()['id']) ? Auth::getUser()['id'] : null;
        $key = $u . Helper::getServer()['REQUEST_URI'];
        $value = json_decode(Cache::get(md5($key)), true);

        if(is_object($value)) {
            $value = self::objectToArray($value);
        }

        return $value;
    }

    /*
     * set cache
     *
     * @return array
     */
    public static function setCache($value, $exp = null)
    {
        $u = isset(Auth::getUser()['id']) ? Auth::getUser()['id'] : null;
        $key = $u . Helper::getServer()['REQUEST_URI'];

        return Cache::set(md5($key), json_encode($value), $exp);
    }

    /*
     * string search index
     * will return false if not found
     *
     * @param string needle
     * @param string stack
     * @return int index
     */
    public static function indexOf($needle, $string)
    {
        $index = strrpos($string, $needle);
        if($index === false) {
            return false;
        }

        return $index;
    }

    /*
     * exec needed headers
     *
     */
    public static function renderHeaders()
    {
        header('Content-Type: application/json');
        // header('Access-Control-Allow-Origin: *');
        // header('Access-Control-Allow-Headers: Origin, Content-Type, Accept, '
            // . 'Authorization, X-Requested-With, Application-Authorization');
        header('Access-Control-Allow-Methods: GET, POST, PATCH, DELETE, OPTIONS');
    }

    /*
     * wrapper for throwing exception
     *
     * @param string error message
     */
    public static function throwError($msg = null)
    {
        throw new Exception($msg);
    }

    /*
     * panics are program errors
     * stops the program for fatal errors
     *
     * @param string error message
     */
    public static function panic($name, $msg)
    {
        self::renderHeaders();

        // set 500 code
        http_response_code(500);

        // it will die and flagged as panic
        self::error($name, $msg, true, true);
    }

    /*
     * gives formatted error
     *
     * @param string error message
     * @return array formatted error
     */
    public static function error($name, $msg, $die = false, $panic = false)
    {
        $error = array('error' => array(
            'panic' => $panic,
            'name' => $name,
            'msg' => $msg));

        // get debug trace when panic
        $stackTrace = [];
        if($panic) {
            $rawStackTrace = debug_backtrace();
            array_shift($rawStackTrace);

            // simplify stack trace
            foreach($rawStackTrace as $trace) {
                $stackTrace[] = $trace['file'] . ':'
                    . $trace['line'] . ' '
                    . $trace['class'] . $trace['type']
                    . $trace['function'] . '()';
            }
        }

        // set to response when requested
        if(self::getParam('debug') !== null) {
            $error['error']['debug_stack'] = $stackTrace;
        }

        // log error
        Log::create(array(
            'type' => $panic ? 'PANIC' : 'ERROR',
            'name' => $name,
            'description' => array(
                'msg' => $msg,
                'debug_stack' => $stackTrace,
            )
        ));

        if($die) {
            die(json_encode($error));
        }

        return $error;
    }

    /*
     * fix preflight error
     *
     */
    public static function fixPreflight()
    {
        // request method type OPTIONS. this hack will prevent preflight errors
		// preflight occurs because our token is handled by Header Request
		// and throws error to endpoints that doesnt have a OPTIONS method
		// to be catch, we will catch those here and assuming OPTIONS method
		// doesnt need anything but 200 respose
		if($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            self::renderHeaders();

			die(json_encode([]));
		}
    }

    /*
     * prints data
     *
     * @param scalar
     * @param bool
     */
    public static function debug($data = null, $die = false)
    {
        control()->inspect($data);

        if($die) {
            die();
        }
    }

    /*
     * check fields if exists
     * search array will return 0 if not found
     *
     * @param array requirement stack
     * @param array field stack
     * @return scalar needle or null
     */
    public static function getMissingFields($data, $required)
    {
        foreach($required as $require) {
            // it will check empty non array $fields
            $d = $data[$require];
            if(!isset($d) || (!is_array($d) && empty(trim($d))) ) {
                return $require;

                break;
            }
        }

        return;
    }

    /*
     * get the client IP address
     *
     * @return string client ip
     */
    public static function getClientIp() {
        if($_SERVER['HTTP_CLIENT_IP']) {
            return $_SERVER['HTTP_CLIENT_IP'];
        } else if($_SERVER['HTTP_X_FORWARDED_FOR']) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else if($_SERVER['HTTP_X_FORWARDED']) {
            return $_SERVER['HTTP_X_FORWARDED'];
        } else if($_SERVER['HTTP_FORWARDED_FOR']) {
            return $_SERVER['HTTP_FORWARDED_FOR'];
        } else if($_SERVER['HTTP_FORWARDED']) {
            return $_SERVER['HTTP_FORWARDED'];
        } else if($_SERVER['REMOTE_ADDR']) {
            return $_SERVER['REMOTE_ADDR'];
        }

        return 'unknown';
    }

    /*
     * redirect
     *
     * @param string url
     */
    public static function redirect($url) {
        header('Location: ' . $url);
        exit;
    }

    /*
     * redirect
     *
     * @param string url
     * @return bool
     */
    public static function validateUrl($url) {
        return (bool) self::indexOf('://', $url);
    }

    /**
     * Converts UNDER_SCORE to CamelCase.
     *
     * @author jaVinc
     *
     * @param string
     * @param string
     * @param string
     *
     * @return string
     */
    public static function toClassName($name, $delimeter = '_', $glue = '')
    {
        return call_user_func_array(function ($glue, $data) use ($glue) {
            return implode($glue, array_map(function ($n) {
                return ucfirst(strtolower($n));
            }, $data));
        }, array(null, explode($delimeter, $name)));
    }

    /* Protected Methods
    --------------------------------------------*/
    /* Private Methods
    --------------------------------------------*/
    // get data based on index
    private static function getData($stack, $index = null)
    {
        if($index === null) {
            return $stack;
        }

        return isset($stack[$index]) ? $stack[$index] : null;
    }

    // convert object to array
    public static function objectToArray($d) {
        return array_map(function($r) {
            if(is_object($r)) {
                return (array) $r;
            }

            return $r;
        }, $d);
    }
}
