<?php //-->

namespace Resources;

use Modules\Resource;

/**
 * Resource CampaignVisit
 * database object of this class object
 *
 * @category   resource
 * @author     javincX
 */
class CampaignVisit
{
    /* Constants
    --------------------------------------------*/
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

    /* Protected Methods
    --------------------------------------------*/
    /* Private Methods
    --------------------------------------------*/
}
