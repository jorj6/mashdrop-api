<?php //-->
/*
 * This file is part of the Openovate Labs Inc. framework library
 * (c) 2013-2014 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */

use Modules\Auth;
use Modules\Helper;
use Services\Permission;

/**
 * The base class for any class that defines a view.
 * A view controls how templates are loaded as well as
 * being the final point where data manipulation can occur.
 *
 * @vendor Openovate
 * @package Framework
 */
class Page extends Eden\Block\Base
{
	const USER_ACCESS_KEY = 'access';

	protected $id = null;
	protected $title = null;
	protected $body = array();
	protected $messages = array();

    public function __construct() {
        if(!isset($this->auth) || $this->auth === true) {
            Auth::setUser(Auth::check());
        }

        // User access control settings
        $settings = control()->config('settings');
        if(Auth::getUser() && isset($settings['uac']) && $settings['uac']) {
            if(property_exists($this, 'permissions')) {
                $this::checkPermission(
                    Auth::getUser(),
                    Helper::getRequestMethod(),
                    $this::$permissions
                );
            }
        }
    }

	/**
	 * returns variables used for templating
	 *
	 * @return array
	 */
	public function getVariables()
	{
		return $this->body;
	}

	/**
	 * Transform block to string
	 *
	 * @param array
	 * @return string
	 */
	public function render()
	{
		Helper::renderHeaders();
		$data = $this->getVariables();

		// check status code if error
		if(isset($data['error'])) {
			http_response_code(400);
		}

		die(json_encode($data));
	}

	protected function getHelpers()
	{
		$urlRoot 	= control()->path('url');
		$cdnRoot	= control()->path('cdn');
		$language 	= control()->language();

		return array(
			'url' => function() use ($urlRoot) {
				echo $urlRoot;
			},

			'cdn' => function() use ($cdnRoot) {
				echo $cdnRoot;
			},

			'_' => function($key) use ($language) {
				echo $language[$key];
			});
	}

    public static function checkPermission($user, $action, $list) {
        if(!isset($list[$action])) {
            return false;
        }

		if(!in_array($list[$action], $user[self::USER_ACCESS_KEY])) {
			Auth::errorCode('ACTION_FORBIDDEN');
		}

        return true;
    }

}
