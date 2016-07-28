<?php //-->

namespace Api\Page\Influence;
use Modules\Rest;
use Modules\Helper;
use Services\Influence\Category as C;

class Category extends \Page
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
        $result = Rest::resource(new C(), true);
        Helper::setCache($result);

        return $result;
    }

    /* Protected Methods
    --------------------------------------------*/
    /* Private Methods
    --------------------------------------------*/
}
