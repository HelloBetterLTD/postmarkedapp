<?php
/**
 * Created by Nivanka Fonseka (nivanka@silverstripers.com).
 * User: nivankafonseka
 * Date: 9/6/15
 * Time: 9:13 AM
 * To change this template use File | Settings | File Templates.
 */

class PostmarkSignature extends DataObject {

	private static $db = array(
		'Email'				=> 'Varchar(100)',
		'IsActive'			=> 'Boolean'
	);

	private static $summary_fields = array(
		'Email',
		'IsActive'
	);

	public function getTitle(){
		return $this->Email;
	}

} 