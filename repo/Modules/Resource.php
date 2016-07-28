<?php //-->

namespace Modules;

use Exception;

/**
 * Module Resource
 * gives power to an resource class object to access database
 * tool, wrapper, and helper of this class object
 *
 * @category   module
 * @author     javincX
 */
class Resource
{
    /* Constants
    --------------------------------------------*/
    const CREATED_FIELD = 'created_at';
    const UPDATED_FIELD = 'updated_at';
    const DELETED_FIELD = 'deleted_at';

    /* Public Properties
    --------------------------------------------*/
    /* Protected Properties
    --------------------------------------------*/
    protected static $resource = null;
    protected static $methodsAvailable = [
        'db',
        'search',
        'find',
        'get',
        'create',
        'update',
        'remove'];

    protected static $optionsAvailable = [
        'fields',
        'relate',
        'limits',
        'sorts',
        'between',
        'filters'];

    /* Public Methods
    --------------------------------------------*/
    // self calling instance
    public static function __callStatic($name, $args)
    {
        // properties
        $method = strtolower(current($args));
        $params = end($args);

        // normalize name
        // camel-cased name will be underscore separated on db name
        self::$resource = strtolower(preg_replace('/([a-zA-Z])(?=[A-Z])/', '$1_', $name));

        // check args is empty, then create
        // a new instance of class
        if($method == 'i') {
            // single instatnce
            return self::singleton();
        }

        // check methods avalable
        if(!in_array($method, self::$methodsAvailable)) {
            Helper::panic(
                'RESOURCE_METHOD_NOT_AVAIALBLE',
                $name . '::' . $method . '()' . ' not available');
        }

        $result = call_user_func_array(array(self, $method), $params);

        // check relationship
        $resourceName = self::getResourcesName($name);
        if(property_exists($resourceName, 'relations')) {
            eval('$relations = $resourceName::$relations;');
            return self::relator($result, $relations, current($params));
        }

        return $result;
    }

    // singleton
    // this will return instance of resource
    // with Campaign resource and statically
    // and able to call methods
    public static function singleton()
    {
         return new Resource();
    }

    public static function db()
    {
        return control()->database();
    }

    public static function search()
    {
        return self::db()->search('`' . self::$resource . '`');
    }

    public static function find($options = array())
    {
        // check options avalable
        foreach($options as $name => $option) {
            if(!in_array($name, self::$optionsAvailable)) {
                Helper::panic(
                    'RESOURCE_OPTION_NOT_AVAIALBLE',
                    self::$resource . '::' . __FUNCTION__ . '()'.
                    ' option ' . $name . ' not available');
            }
        }

        $search = self::search();

        // fields
        if($property = self::isPropertyExists($options, 'fields')) {
            $search->setColumns(implode(', ', $property));
        }

        // limits
        if($property = self::isPropertyExists($options, 'limits')) {
            if(count($property) != 2) {
                return false;
            }

            $search->setRange($property[1])->setStart($property[0]);
        }

        // sorts
        if($property = self::isPropertyExists($options, 'sorts')) {
            foreach($property as $field => $type) {
                $search->addSort($field, strtoupper($type));
            }
        }

        // between
        if($property = self::isPropertyExists($options, 'between')) {
            foreach($property as $field => $dates) {
                if(count($dates) != 2) {
                    return false;
                }

                $field = sprintf('%s', $field);
                $options['filters'][] = array($field . ' BETWEEN %s AND %s', current($dates), end($dates));
            }
        }

        // filters
        if($property = self::isPropertyExists($options, 'filters')) {
            $exception = ['key'];
            foreach($property as $key => $value) {
                // if exception convert to array so it will
                // treat as a manual entry
                if(!is_numeric($key) && in_array($key, $exception)) {
                    $value = array('`' . $key . '` = %s', $value);
                }

                // if array means manual
                // manual adding of filter
                if(is_array($value)) {
                    call_user_func_array(array(
                        $search, 'addFilter'), $value);

                    continue;
                }

                $filterMethod = 'filterBy' . ucfirst(strtolower($key));
                $search->$filterMethod($value);
            }
        }

        // except soft deleted
        $search->addFilter(self::DELETED_FIELD . ' IS NULL');

        try {
            $data = $search->getRows();

            // except soft deleted
            foreach($data as $key => $value) {
                unset($data[$key][self::DELETED_FIELD]);
            }

            return $data;
        } catch (Exception $e) {
            Helper::panic(
                'RESOURCE_FIND_EXCEPTION',
                $e->getMessage());
        }
    }

    public static function get($options = array())
    {
        // check empty options
        if(empty($options)) {
            Helper::panic(
                'RESOURCE_OPTION_REQUIRED',
                self::$resource . '::' . __FUNCTION__ .
                '() options required, empty given');

            return;
        }

        // if filter not array it means its an Id
        // special cases of table column naming
        // convention, In this case column id is `id`
        if(!is_array($options)) {
            $options = array('filters' => array('id' => $options));
        }

        // single row
        $options['limits'] = array(0, 1);

        return current(self::find($options));
    }

    public static function create($fields)
    {
       
        // cast array
        $fields = (array) $fields;

        // add escaper for sql special words
        $fields = self::sqlEscape($fields);

        // check empty fields
        if(empty($fields)) {
            Helper::panic(
                'RESOURCE_FIELDS_REQUIRED',
                self::$resource . '::' . __FUNCTION__ .
                '() fields required, empty given');

            return;
        }

        // add meta
        $fields[self::CREATED_FIELD] = date("Y-m-d H:i:s");
        $fields[self::UPDATED_FIELD] = date("Y-m-d H:i:s");

        
        try {
            
            $id = self::db()
                ->insertRow('`' . self::$resource . '`', $fields)
                ->getLastInsertedId();
            
            return self::get($id);
        } catch (Exception $e) {
            Helper::panic(
                'RESOURCE_CREATE_EXCEPTION',
                $e->getMessage());
        }
    }

    public static function update($fields, $filters)
    {
        // check empty fields || filters
        if(empty($fields) || empty($filters)) {
            Helper::panic(
                'RESOURCE_FIELDS_AND_FILTERS_REQUIRED',
                self::$resource . '::' . __FUNCTION__ .
                '() fields & filters are required, empty given');

            return;
        }

        // need to pass filters param when associative
        // because it will use find method
        $data = self::get(is_array($filters)
            ? array('filters' => $filters) : $filters);

        // check if exists
        if(empty($data)) {
            return;
        }

        // parse filters
        if(is_array($filters)) {
            foreach($filters as $key => $filter) {
                $filters[] = array($key . '=%s', (int) $filter);
                unset($filters[$key]);
            }
        } else {
            // if filter not array it means its an Id
            // special cases of table column naming
            // convention, In this case column id is `id`
            $filters = [array('id=%s', $filters)];
        }

        // update meta
        $fields[self::UPDATED_FIELD] = date("Y-m-d H:i:s");

        // new data
        $data = array_merge($data, $fields);

        try {
            self::db()->updateRows(
                '`' . self::$resource . '`',
                self::sqlEscape($fields),
                $filters);

            return $fields;
        } catch (Exception $e) {
            Helper::panic($e->getMessage());
        }
    }

    public static function remove($filters)
    {
        // check empty filters
        if(empty($filters)) {
            Helper::panic(
                'RESOURCE_FILTERS_REQUIRED',
                self::$resource . '::' . __FUNCTION__ .
                '() filters required');

            return false;
        }

        // soft remove only
        if(self::update(array(
            self::DELETED_FIELD => date("Y-m-d H:i:s")),
            $filters)) {
            return true;
        }
    }

    public static function relator($result, $resources, $options)
    {
        $relateKey = 'relate';

        if(empty($options[$relateKey]) && !is_array($options[$relateKey])) {
            return $result;
        }

        // check if multi dimensional array
        // it means its a find()
        $isMulti = !is_array(current($result));
        if($isMulti) {
            $result = array($result);
        }

        foreach($result as $key => $row)  {
            foreach($resources as $r) {
                if(in_array($r, $options[$relateKey])) {
                    $id = strtolower($r) . '_id';

                    // check if empty
                    if(empty($row[$id])) {
                        return array();
                    }

                    // call function dynamically
                    $data = call_user_func(
                        self::getResourcesName($r) . '::get', $row[$id]);
                    // if not found make sure its null
                    $result[$key][$r] = empty($data) ? null : $data;
                    unset($result[$key][$id]);
                }
            }
        }

        if($isMulti) {
            $result = current($result);
        }

        return $result;
    }

    /* Protected Methods
    --------------------------------------------*/
    /* Private Methods
    --------------------------------------------*/
    // Private ctor so nobody else can instance it
    private function __construct()
    {
    }

    private static function isPropertyExists($object, $property)
    {
        if(isset($object[$property]) && is_array($object[$property])) {
            return $object[$property];
        }

        return false;
    }

    private static function getResourcesName($name)
    {
        return 'Resources\\' . Helper::toClassName($name);
    }

    private static function sqlEscape($fields, $escape = true)
    {
        $e = '`';
        foreach($fields as $key => $field) {
            $k = $escape ? $e . $key . $e : str_replace($e, '', $key);
            $fields[$k] = $field;
            unset($fields[$key]);
        }

        return $fields;
    }
}
