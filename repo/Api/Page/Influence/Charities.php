<?php //-->

namespace Api\Page\Influence;
use Modules\Rest;
use Modules\Helper;
use Modules\Resource;
use Services\Influence\Charities as C;

class Charities extends \Page
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
      
        //$categories =  C::getsocialcategories();
        $method = Helper::getRequestMethod();
        $result = Rest::resource(new C(), $method);
        echo "<pre>"; print_r($result); die;
        Helper::setCache($result);

        return $result;
    }

    /* Protected Methods
    --------------------------------------------*/
    /* Private Methods
    --------------------------------------------*/
}
