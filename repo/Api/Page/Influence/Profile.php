<?php

//-->

namespace Api\Page\Influence;

use Modules\Rest;
use Modules\Helper;
use Services\Influence\Profile as P;

class Profile extends \Page {
    /* Constants
      -------------------------------------------- */
    /* Public Properties
      -------------------------------------------- */
    /* Protected Properties
      -------------------------------------------- */
    /* Public Methods
      -------------------------------------------- */

    public function getVariables() {
        $result = Rest::resource(new P(), true);
        Helper::setCache($result);

        return $result;
    }

    /* Protected Methods
      -------------------------------------------- */
    /* Private Methods
      -------------------------------------------- */
}
