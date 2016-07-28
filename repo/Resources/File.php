<?php //-->

namespace Resources;

use Modules\Resource;
use Modules\Helper;

/**
 * Resource File
 * database object of this class object
 *
 * @category   resource
 * @author     javincX
 */
class File
{
    /* Constants
    --------------------------------------------*/
    const URL_FIELD = 'url';

    /* Public Properties
    --------------------------------------------*/
    /* Protected Properties
    --------------------------------------------*/
    /* Public Methods
    --------------------------------------------*/
    public static function __callStatic($name, $args)
    {
        $table = end(explode('\\', get_class()));
        return Resource::$table($name, $args);
    }

    public static function find($options)
    {
        $files = self::i()->find($options);

        return self::resultModifier($files);
    }

    public static function get($options)
    {
        $file = self::i()->get($options);

        return current(self::resultModifier(array($file)));
    }

    /* Protected Methods
    --------------------------------------------*/
    /* Private Methods
    --------------------------------------------*/
    private static function resultModifier($result = array())
    {
        // append url
        foreach($result as $key => $file) {
            // provide file image url
            // check if image type then use image by default
            $type = 'raw';
            if(Helper::indexOf('image', $file['mime']) !== false) {
                $type = 'image';
            }

            $result[$key][self::URL_FIELD] = join('/', [
                Helper::getSetting('url_root'),
                'file',
                $type,
                $file['uuid']]);
        }

        return $result;
    }
}
