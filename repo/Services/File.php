<?php //-->

namespace Services;

use Exception;
use Modules\Helper;
use Modules\Upload;
use Resources\File as F;

/**
 * Service File
 * business logic of this class object
 *
 * @category   service
 * @author     javincX
 */
class File
{
    /* Constants
    --------------------------------------------*/
    const UPLOAD_KEY = 'file';
    const CACHE_RENDER = true;
    const CACHE_AGE = '2 day';

    /* Public Properties
    --------------------------------------------*/
    /* Protected Properties
    --------------------------------------------*/
    protected static $filePath = 'upload';
    protected static $allowedMime = array(
            'image/jpeg',
            'image/png');

    /* Private Properties
    --------------------------------------------*/
    /* Public Methods
    --------------------------------------------*/
    public static function __callStatic($name, $args)
    {
        return F::$name(current($args), end($args));
    }

    public static function getData($uuid)
    {
        // search uuid if exists
        $data = F::get(array(
            'filters' => array(
                'uuid' => $uuid)));

        // check empty
        if(empty($data)) {
            return false;
        }

        // add file path
        $data['path'] = self::getPath() . '/' . $uuid . '.' . $data['extension'];

        return $data;
    }

    public static function getFile($uuid)
    {
        $data = self::getData($uuid);
        // check if empty
        if(empty($data)) {
            return false;
        }

        // dispose file
        if($data) {
            self::dispose($data['path'], $data['name'], $data['mime'], $data['size']);
        }

        return false;
    }

    public static function dispose($path, $name, $mime, $size)
    {
        $fp = fopen($path, 'rb');

        // send the right headers
        header('Content-Disposition: inline; filename="' . $name . '"');
        header('Content-Type: ' . $mime);
        header('Content-Length: ' . $size);

        // dump the picture and stop the script
        fpassthru($fp);

        exit;
    }

    public static function getPath() {
        return control()->path(self::$filePath);
    }

    public static function upload($file)
    {
        $path = self::getPath();

        // init Upload
        $upload = new Upload();
        $upload->setPath($path)->setAllowedMime(self::$allowedMime);

        // uploading
        try {
            $data = $upload->process($file);

            return F::create(array(
                'uuid' => $data['uuid'],
                'name' => $data['meta']['name'],
                'extension' => $data['extension'],
                'mime' => $data['meta']['type'],
                'size' => $data['meta']['size']));
        } catch (Exception $e) {
            return Helper::error('FILE_UPLOAD_ERROR',
                $e->getMessage());
        }

        return Helper::error('FILE_UPLOAD_UNKNOWN_ERROR',
            'something when wrong');
    }

    public static function renderImage()
    {
        // variables=0 is the dimension
        $segment = Helper::getSegment();
        $uuid = $segment[0];
        $dimension = $segment[1];

        //  check if param complete
        if(count($segment) == 0) {
            die('invalid parameters');
        }

        // http://host.com/image/400x200/ed9b3d2e9c84f59c513bb0e5081f0945
        if(($tmp = explode('x', $dimension)) && sizeof($tmp) > 1){
            $width = intval($tmp[0]);
            $height = intval($tmp[1]);
        }

        // get image path
        $file = self::getData($uuid);
        if(empty($file)) {
            die('file not found');
        }

        // cache mode
        if(self::CACHE_RENDER) {
            self::cacheMode();
        }

        // load the image object
        $image = eden('image', $file['path'], strtolower($file['extension']));
        // keep original ratio
        if(empty($dimension)) {
            self::dispose($file['path'], $file['name'], $file['mime'], $file['size']);
        // parameter passed is one /image/*/300
        } else if($dimension > 0 && !isset($height)) {
            // resize the image
            $image->resize(null, $dimension);
            // crop the image
            $image->crop($dimension, $dimension);
        // parameter passed is for width only /image/*/300x0
        } else if($dimension > 0 && $height == 0) {
            // resize width only
            $image->resize($width, null);
        // parameter passed is for width only /image/*/300x0
        } else if($dimension > 0 && $width == 0) {
            // resize width only
            $image->resize(null, $height);
        // parameter passed is for width and height /image/*/300x200
        } else if($dimension > 0 && isset($height)) {
            // scale the image to fit specific dimensions
            $image->scale($width, $height);
            // crop the image
            $image->crop($width, $height);
        }

        header('Content-Type: image/' . $file['extension']);

        die($image);
    }

    private static function cacheMode()
    {
        session_start();
        header('Cache-Control: private, max-age=10800, pre-check=10800');
        header('Pragma: private');
        header('Expires: ' . date(DATE_RFC822, strtotime(' ' . self::CACHE_AGE)));
    }

    /* Protected Methods
    --------------------------------------------*/
    /* Private Methods
    --------------------------------------------*/
}
