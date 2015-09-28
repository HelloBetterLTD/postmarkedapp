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

Object::add_extension($clientClass, 'CustomerExtension');

require_once BASE_PATH . '/vendor/autoload.php';

global $databaseConfig;
$databaseConfig['type'] = 'PostmarkMySQLDatabase';


define('POSTMARK_PATH', dirname(__FILE__));
define('POSTMARK_RELATIVE_PATH', str_replace(dirname(POSTMARK_PATH) . '/', '', POSTMARK_PATH));
