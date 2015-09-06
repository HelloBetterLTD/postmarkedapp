<?php
/**
 * Created by Nivanka Fonseka (nivanka@silverstripers.com).
 * User: nivankafonseka
 * Date: 9/6/15
 * Time: 8:52 AM
 * To change this template use File | Settings | File Templates.
 */

$clientClass = Config::inst()->get('PostmarkAdmin', 'member_class');

Config::inst()->update('CRMAdmin', 'managed_models', array(
	$clientClass
));

// echo BASE_PATH . '/vendor/autoload.php'; die();

require_once BASE_PATH . '/vendor/autoload.php';