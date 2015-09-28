<?php
/**
 * Created by Nivanka Fonseka (nivanka@silverstripers.com).
 * User: nivankafonseka
 * Date: 9/25/15
 * Time: 5:40 PM
 * To change this template use File | Settings | File Templates.
 */

class CustomerExtension extends DataExtension {

	private static $many_many = array(
		'Tag'			=> 'CustomerTag',
		'Statuses'		=> 'CustomerStatus'
	);

} 