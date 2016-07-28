<?php //-->

namespace Api\Page\Influence;

use Modules\Rest;
use Modules\Helper;
use Modules\Resource;
use Services\Influence\Paypalemail as PE;

class Paypalemail extends \Page
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
        $result = Rest::resource(new PE(), TRUE);
        //echo "<pre>"; print_r($result); die;
        Helper::setCache($result);

        return $result;
    }

    /* Protected Methods
    --------------------------------------------*/
    /* Private Methods
    --------------------------------------------*/
}
