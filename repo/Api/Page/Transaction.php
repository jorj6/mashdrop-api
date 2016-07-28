<?php //-->

namespace Api\Page;

use Modules\Rest;
use Services\Permission;
use Services\Transaction as T;

class Transaction extends \Page
{
    /* Constants
    --------------------------------------------*/
    /* Public Properties
    --------------------------------------------*/
    public static $permissions = array(
        'GET' => Permission::TRANSACTION_VIEW,
        'POST' => Permission::TRANSACTION_CREATE,
        'PATCH' => Permission::TRANSACTION_UPDATE,
        'DELETE' => Permission::TRANSACTION_REMOVE,
    );

    /* Protected Properties
    --------------------------------------------*/
    /* Public Methods
    --------------------------------------------*/
    public function getVariables()
    {
        return Rest::resource(new T(), true);
    }

    /* Protected Methods
    --------------------------------------------*/
    /* Private Methods
    --------------------------------------------*/
}
