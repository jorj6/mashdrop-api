<?php //-->

namespace Api\Page\File;

use Modules\Helper;
use Services\File;

class Raw extends \Page 
{
    /* Constants
    --------------------------------------------*/
    /* Public Properties
    --------------------------------------------*/
    public $auth = false;

    /* Protected Properties
    --------------------------------------------*/
    /* Public Methods
    --------------------------------------------*/
    public function getVariables()
    {   
        $uuid = Helper::getSegment(0);
        
        // retrieve file
        if($data = File::getFile($uuid)) {
            return $data;
        }

        die('file not found');
    }

    /* Protected Methods
    --------------------------------------------*/
    /* Private Methods
    --------------------------------------------*/
}