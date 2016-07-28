<?php //-->

namespace Modules;

use Exception;

/**
 * Module Upload
 * pluggable module for handling file uploads
 * tool, wrapper, and helper of this class object
 *
 * @category   module
 * @author     javincX
 */
class Upload
{
    /* Constants
    -------------------------------*/
    /* Public Properties
    -------------------------------*/
    public $fileName = null;
    public $path = null;
    public $config = array(
        'limit_size' => 10000000,
        'allowed_mime' => array('text/csv'));

    /* Protected Properties
    -------------------------------*/
    /* Magic
    -------------------------------*/
    public function __construct()
    {
        // get temp upload path
        $this->path = self::normalizePath(sys_get_temp_dir()) . $this->path;
    }

    /* Public Methods
    -------------------------------*/
    /**
     * set allowed mime
     *
     * @param string | array
     * @return this
     */
    public function setAllowedMime($mime)
    {
        $this->setUploadConfig(array('allowed_mime' => $mime));

        return $this;
    }

    /**
     * set limit size
     *
     * @param string
     * @return this
     */
    public function setLimitSize($size)
    {
        $this->setUploadConfig(array('limit_size' => $size));

        return $this;
    }

    /**
     * set name of file
     * default filename is UUID
     *
     * @param string
     * @return this
     */
    public function setFileName($name)
    {
        if(trim($name) == '') {
            self::throwException('Filename set but empty');
        }

        $this->fileName = $name;

        return $this;
    }

    /**
     * set name of file
     * default file path is system temp path
     *
     * @param string
     * @return this
     */
    public function setPath($path)
    {
        if(trim($path) == '') {
            self::throwException('Upload path set but empty');
        }

        $this->path = $path;

        return $this;
    }

    /**
     * upload configuration
     * default config
     *      limit_size      1000000
     *      allowed_mime    text/csv
     *
     * accepts multiple allowed mimes
     *
     * @param array config
     * @return this
     */
    public function setUploadConfig(array $config)
    {
        self::checkEmpty($config, 'upload config empty');

        $size = 'limit_size';
        $mime = 'allowed_mime';

        // size setting
        if(isset($config[$size])) {
            $this->config[$size];
        }

        // allowed mime type
        $allowedMime = $config[$mime];
        if(isset($allowedMime)) {
            self::checkEmpty($allowedMime, 'allowed mime config empty');

            // if not array make it one
            if(!is_array($allowedMime)) {
                $allowedMime = array($allowedMime);
            }

            foreach($allowedMime as $value) {
                $this->config[$mime][] = $value;
            }
        }

        return $this;
    }

    /**
     * upload file
     *
     * @param array file
     * @return this
     */
    public function process(array $file)
    {
        self::checkEmpty($file, 'uploading file is empty');

        // validate file
        if(!isset($file['error']) || is_array($file['error'])) {
            self::throwException('invalid parameters');
        }

        // check errors
        switch ($file['error']) {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_NO_FILE:
                self::throwException('no file sent');
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                self::throwException('exceeded filesize limit');
            default:
                self::throwException('unknown errors');
        }

        // check file size
        if($file['size'] > $this->config['limit_size']) {
            self::throwException('exceeded filesize limit of '
                . ($this->config['limit_size'] / 1000000) . ' mb');
        }

        // check file mime exists
        if((!isset($file['type']) || $file['type'] == '')
        && (!isset($file['mime']) || $file['mime'] == '')) {
            self::throwException('mime or type not exists');
        }

        // check allowed mime type
        $type = isset($file['mime']) ? $file['mime'] : $file['type'];
        if(!in_array($type, $this->config['allowed_mime'])) {
            self::throwException('"' . $type . '" type not allowed');
        }

        // setup file and path
        $separator = '.';
        $extension = explode($separator, $file['name']);
        $extension = end($extension);

        $name = is_null($this->fileName) ? self::getUID() : $this->fileName;
        $path = self::normalizePath($this->path);

        // validate path exist
        if(!file_exists($path)) {
            self::throwException('"' . $path . '" path not exists');
        }

        // validate if writable
        if(!is_writable($path)) {
            self::throwException('"' . $path . '" path not writable');
        }

        // move file to temp
        if(!move_uploaded_file($file['tmp_name'], $path . $name . $separator . $extension)) {
            self::throwException('failed to move uploaded file');
        }

        return array(
            'uuid' => $name,
            'extension' => $extension,
            'path' => $path,
            'meta' => $file);
    }

    /* Protected Methods
    -------------------------------*/
    // check if empty
    protected static function checkEmpty($data, $msg = 'Empty array passed to argument')
    {
        if(empty($data)) {
            self::throwException($msg);
        }

        return true;
    }

    // throw error
    protected static function throwException($msg)
    {
        throw new Exception($msg, 1);
    }

    /* Private Methods
    -------------------------------*/
    // generate UUID
    private static function getUID($data = null)
    {
        return md5(is_null($data) ? microtime() : $data);
    }

    // clean path
    private static function normalizePath($path)
    {
        return rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
    }
}
