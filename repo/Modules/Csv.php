<?php //-->

namespace Modules;

use Exception;

/**
 * Module Csv
 * General available methods for CSV import and export
 *
 * @package    Eden
 * @category   utility
 * @author     javincX
 */
class Csv extends \Eden\Core\Base
{
    /* Constants
    -------------------------------*/
    /* Public Properties
    -------------------------------*/
    public $clean = false;
    public $fileName = 'csv-export.csv';
    public $header = array();
    public $schema = array();
    public $uploadConfig = array(
        'limit_size' => 1000000,
        'allowed_mime' => array(
            'text/csv',
            'application/octet-stream',
            'application/vnd.ms-excel'));

    /* Protected Properties
    -------------------------------*/

    /* Magic
    -------------------------------*/
    public function __construct($data = null)
    {
        // generate temp filepath
        $this->tempCsvPath = rtrim(sys_get_temp_dir(), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . self::getTempFile();
    }

    public static function i()
    {
        return self::getSingleton(__CLASS__);
    }

    /* Public Methods
    -------------------------------*/
    /*
     * add single row on csv
     *
     * @param array
     * @return this
     */
    public function addRow(array $data)
    {
        self::checkEmpty($data);

        // append to csv
        $this->append($data);

        return $this;
    }

    /*
     * add multi row on csv
     *
     * @param array
     * @return this
     */
    public function addRows(array $data)
    {
        self::check2DimeArray($data);

        foreach($data as $value) {
            $this->addRow($value);
        }

        return $this;
    }

    /*
     * will delete the temp file
     *
     * @return this
     */
    public function clean()
    {
        // delete temp file
        if(file_exists($this->tempCsvPath)) {
            unlink($this->tempCsvPath);
        }

        return $this;
    }

    /*
     * download as csv, reads the temp file and delete after
     *
     * @param array
     */
    public function download(array $data = array())
    {
        $path = $this->tempCsvPath;

        header('Pragma: public');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Cache-Control: private', false);
        header('Content-Transfer-Encoding: binary');  // For Gecko browsers mainly
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s', filemtime($path)) . ' GMT');
        header('Accept-Ranges: bytes');  // For download resume
        header('Content-Length: ' . filesize($path));  // File size
        header('Content-Encoding: none');
        header('Content-Type: text/csv');  // Change this mime type if the file is not PDF
        header('Content-Disposition: attachment; filename=' . $this->fileName);  // Make the browser display the Save As dialog

        // for quick download
        if(!empty($data)) {
            $this->addRows($data);
        }

        // get data
        die($this->getCsvData($path));
    }

    /*
     * parse csv
     * when param is null it will refer to tempCsvPath
     * when schema is not null it will check and assign keys to rows
     * the column name must be all thesame else it will go losely
     *
     * @param string file path
     * @param boolean unlink on parse
     * @return array csv data
     */
    public function parse($file = null)
    {
        $data = array();

        $filePath = $this->tempCsvPath;
        if($file) {
            $filePath = $file;
        }

        // array mode
        $raw = self::getRawData($filePath);

        // parse into array
        $rows = array_filter($raw);
        $header = current($rows);

        // assoc mode
        if(!empty($this->schema)) {
            foreach($header as $key => $columnName) {
                $columnName = $header[$key] = trim($columnName);
                foreach($this->schema as $index => $value) {
                    if(trim(strtolower($value)) == strtolower($columnName)) {
                        $header[$key] = $index;

                        break;
                    }
                }
            }
        }

        $headerCount = count($header);
        foreach($rows as $row) {
            if($headerCount == count($row)) {
                $data[] = array_combine($header, $row);

                continue;
            }

            $data[] = $row;
        }

        return $data;
    }

    /*
     * set name of exported file
     * default filename is 'csv-export.csv'
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
        $this->tempCsvPath = $this->tempCsvPath . '-' . $name;

        return $this;
    }

    /*
     * sets csv headers or column names
     *
     * @param array
     * @return this
     */
    public function setHeader(array $data)
    {
        self::checkEmpty($data);

        $this->header = $data;

        return $this;
    }

    /*
     * set schema for organizing data
     * array values are used as header when header is not set
     *
     * @param array
     * @return this
     */
    public function setSchema(array $data)
    {
        self::checkEmpty($data);

        $this->schema = $data;

        return $this;
    }

    /*
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
            $this->uploadConfig[$size];
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
                $this->uploadConfig[$mime][] = $value;
            }
        }

        return $this;
    }

    /*
     * upload file
     *
     * @param array file
     * @return this
     */
    public function upload(array $file)
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
        if($file['size'] > $this->uploadConfig['limit_size']) {
            self::throwException('exceeded filesize limit');
        }

        // check allowed mime type
        $type = isset($file['mime']) ? $file['mime'] : $file['type'];
        if(!in_array($type, $this->uploadConfig['allowed_mime'])) {
            self::throwException('"' . $type . '" type not allowed');
        }

        // check if file exists, then delete the old
        if(file_exists($this->tempCsvPath)) {
            unlink($this->tempCsvPath);
        }

        // move file to temp
        if(!move_uploaded_file($file['tmp_name'], $this->tempCsvPath)) {
            self::throwException('failed to move uploaded file');
        }

        return $this;
    }

    /* Protected Methods
    -------------------------------*/
    // check if perfect 2 dimensional array
    protected static function check2DimeArray(array $data)
    {
        $msg = 'Not a perfect two-dimensional array';

        self::checkEmpty($data);

        if(is_array($data)) {
            foreach($data as $key => $value) {
                self::checkEmpty($data, $msg);
            }

            return true;
        }

        self::throwException($msg);
    }

    // check if empty
    protected static function checkEmpty($data, $msg = 'Empty array passed to argument')
    {
        if(empty($data)) {
            self::throwException($msg);
        }

        return true;
    }

    // get raw file data
    protected static function getRawData($filePath)
    {
        // check if file exist
        if(!file_exists($filePath)) {
            $msg = 'Temporary file not Exist';

            self::throwException($msg);

            return $msg;
        }

        $temp = fopen($filePath, 'r');

        $csv = array();
        while ($data = fgetcsv($temp)) {
            $csv[] = $data;
        }

        fclose($temp);

        return $csv;
    }

    // throw error
    protected static function throwException($msg)
    {
        throw new Exception($msg, 1);
    }

    /* Private Methods
    -------------------------------*/
    // append to csv
    private function append($body)
    {
        $data = array();

        // format data
        foreach($this->schema as $name => $title) {
            if(isset($body[$name])) {
                $data[$name] = $body[$name];

                continue;
            }

            // if field not exist assign null
            $data[$name] = null;
        }

        if(is_array($data)) {
            $temp = fopen($this->tempCsvPath, 'a');
            fputcsv($temp, $data);

            fclose($temp);

            return true;
        }

        return false;
    }

    // get csv data
    private function getCsvData($filePath)
    {
        // prioritized header
        $header = empty($this->header) ? $this->schema : $this->header;

        // get data and format by order
        $data = self::getRawData($filePath);
        foreach($data as $key => $value) {
            $data[$key] = array_combine(array_flip($header), $value);
        }

        // prepend headers
        $csv = array_merge(array($header), $data);
        $this->clean();

        // print_r($csv);exit();

        $this->addRows($csv);

        // append body
        try {
            // this is necessary in order to get it to actually download the file,
            // otherwise it will be 0Kb
            $csv = readfile($filePath);

            // delete temp file
            $this->clean();
        } catch (Exception $e) {
            $csv = $e->getMessage();
        }

        return $csv;
    }

    // generate unique name based on uri
    private static function getTempFile()
    {
        return md5($_SERVER['REDIRECT_URL']);
    }
}
