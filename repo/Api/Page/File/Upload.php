<?php //-->

namespace Api\Page\File;

use Modules\Helper;
use Services\File;

class Upload extends \Page 
{
    /* Constants
    --------------------------------------------*/
    /* Public Properties
    --------------------------------------------*/
    /* Protected Properties
    --------------------------------------------*/
    /* Public Methods
    --------------------------------------------*/
    public function getVariables()
    {   
        // upload & check if key is upload
        if(Helper::getRequestMethod() == 'POST') {
            $files = Helper::getFile();
            if(empty($files)) {
                return Helper::error(
                    'FILE_UPLOAD_EMPTY',
                    'no file to be uploaded');
            }

            // get file input
            $file = current($files);

            return File::upload($file);
        }

        return Helper::error(
            'METHOD_NOT_ALLOWED',
            'method not allowed');
    }

    /* Protected Methods
    --------------------------------------------*/
    /* Private Methods
    --------------------------------------------*/
}