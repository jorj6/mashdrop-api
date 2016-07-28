<?php //-->

namespace Services;

use Modules\Resource;
use Resources\Permission as P;
use Resources\RolePermission;

/**
 * Service Permission
 * business logic of this class object
 *
 * @category   service
 * @author     javincX
 */
class Permission
{
    /* Constants
    --------------------------------------------*/
    const USER_VIEW = 'user_view';
    const USER_CREATE = 'user_create';
    const USER_UPDATE = 'user_update';
    const USER_REMOVE = 'user_remove';

    const CAMPAIGN_VIEW = 'campaign_view';
    const CAMPAIGN_CREATE = 'campaign_create';
    const CAMPAIGN_UPDATE = 'campaign_update';
    const CAMPAIGN_REMOVE = 'campaign_remove';

    const TRANSACTION_VIEW = 'transaction_view';
    const TRANSACTION_CREATE = 'transaction_create';
    const TRANSACTION_UPDATE = 'transaction_update';
    const TRANSACTION_REMOVE = 'transaction_remove';

    /* Public Properties
    --------------------------------------------*/
    /* Protected Properties
    --------------------------------------------*/
    /* Private Properties
    --------------------------------------------*/
    /* Public Methods
    --------------------------------------------*/
    public static function __callStatic($name, $args)
    {
        return P::$name(current($args), end($args));
    }

    // get access control list
    public static function getAccess($roleId)
    {
        return RolePermission::find(array(
            'filters' => array(
                'role_id' => $roleId),
            'relate' => array('permission')));
    }

    /* Protected Methods
    --------------------------------------------*/
    /* Private Methods
    --------------------------------------------*/
}
