<?php //-->

namespace Modules;

/**
 * Module Rest
 * capturing request method and its equivalent method
 * tool, wrapper, and helper of this class object
 *
 * @category   module
 * @author     javincX
 */
class Rest
{
    /* Constants
    --------------------------------------------*/
    const TYPE_FIELD = 'type';
    const ADMIN_TYPE = 'admin';

    /* Public Properties
    --------------------------------------------*/
    /* Protected Properties
    --------------------------------------------*/
    protected static $user;
    protected static $methodsAvailable = array(
        'GET' => 'find',
        'POST' => 'create',
        'PATCH' => 'update',
        'DELETE' => 'remove');

    /* Public Methods
    --------------------------------------------*/
    public static function resource($resource, $auth)
    {
        return self::call($resource, Helper::getRequestMethod());
    }

    public static function call($resource, $method)
    {
        // check empty resource || method
        if(empty($resource) || empty($method)) {
            Helper::panic(
                'REST_RESOURCE_REQUIRED',
                Helper::$resource . '::' . __FUNCTION__ .
                '() resource & method are required, empty given');

            return;
        }

        // check available methods
        if(!array_key_exists($method, self::$methodsAvailable)) {
            Helper::panic(
                'REST_METHOD_NOT_AVAILABLE',
                $method . ' method not available');
        }

        // rest call
        return self::process($method, $resource, self::$methodsAvailable[$method]);
    }

    /* Protected Methods
    --------------------------------------------*/
    /* Private Methods
    --------------------------------------------*/
    private static function auth()
    {
        // check user type
        // auth will identify type user
        // PUBLIC type user should only control
        // their own data by using id, while
        // ADMIN type user will not
        if($user = Auth::getUser()) {
            if(isset($user[self::TYPE_FIELD])
            && $user[self::TYPE_FIELD] == self::ADMIN_TYPE) {
                return self::ADMIN_TYPE;
            }

            return $user['id'];
        }
    }

    private static function process($method, $resource, $resourceMethod)
    {
        $method = strtolower($method);
        if(!$method) {
            Helper::panic(
                'REST_METHOD_NOT_ALLOWED',
                $method . ' method not allowed');
        }

        // check request authentication
        return self::$method($resource, $resourceMethod);
    }

    private static function get($resource, $resourceMethod)
    {
        $options = Helper::getParam();
       
        // check if singles
        if($id = Helper::getSegment(0)) {
            $options['filters']['id'] = $id;
            $options['limits'] = [0, 1];

            return $resource::get($options);
        }

        return $resource::$resourceMethod($options);
    }

    private static function post($resource, $resourceMethod)
    {
        // no id
        if((bool) Helper::getSegment(0)) {
            Helper::panic(
                'REST_ID_MUST_NOT_DEFINED',
                'Id must not define');
        }

        $payload = Helper::getJson();

        // checking requirements
        if(property_exists($resource, 'required') && !empty($resource::$required[$resourceMethod])
        && $field = Helper::getMissingFields($payload, $resource::$required[$resourceMethod])) {
            return Helper::error(
                'REST_FIELDS_REQUIRED',
                $field . ' required, empty given');
        }

        return $resource::$resourceMethod($payload);
    }

    private static function patch($resource, $resourceMethod)
    {
        // check if singles
        if($id = Helper::getSegment(0)) {
            return $resource::$resourceMethod(
                Helper::getJson(), $id);
        }

        Helper::panic(
            'REST_ID_NOT_DEFINED',
            'Id not defined');
    }

    private static function delete($resource, $resourceMethod)
    {
        // check if singles
        if($id = Helper::getSegment(0)) {
            return $resource::$resourceMethod($id);
        }

        Helper::panic(
            'REST_ID_NOT_DEFINED',
            'Id not defined');
    }
}
