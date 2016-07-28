<?php //-->

$host = 'http://api.dev.mashdrop';
$appHost = 'http://dev.mashdrop';

return array(
	'url_root' => $host,
	'app_root' => $appHost,
	'cdn_root' => '',
	'i18n' => 'en_US',
	'eden_debug' => true,
	'uac' => true,
	'debug_mode' => E_ALL,
	'server_timezone' => 'Asia/Manila',
	'default_page' => 'index',
	'cache' => array(
		'expiration' => 3600,
		'prefix' => 'mashdrop'
	),
	'stripe' => array(
		'secret_key' => '{secret-key}',
		'publishable_key' => 'pk_test_9jQD7BAJwsvldJKn8AEw8DdR'),
	'paypal' => array(
		'live' => false,
		'user' => 'seller_api1.mashdrop.com',
		'pass' => 'X89RB5NWN2YEPFA2',
		'signature' => 'Azj6cn6eKbqB9iOeoUiZgEx14SHuAs6R.na3Tb4JRpo5CMSTEL9DcmIK'),
	'facebook' => array(
		'app_id' => '272233649811917',
		'app_secret' => '250767e4568ca942a291182bcba3c818',
		'default_graph_version' => 'v2.3'),
	'mail' => array(
		'ses' => array(),
		'smtp' => array(
				'default' => true,
				'host' => 'smtp.live.com',
				'user' => 'dev@chiligarlic.com',
				'pass' => '{pass}',
				'name' => 'Mashdrop.com')),
	'jwt' => array(
		'key' => 'whatthefucklogic',
		'algo' => array('HS256'),
		'payload' => array(
			'iss' => $host,
            'aud' => $appHost,
            'iat' => 1356999524,
            'nbf' => 1357000000)));
