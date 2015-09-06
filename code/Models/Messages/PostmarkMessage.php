<?php
/**
 * Created by Nivanka Fonseka (nivanka@silverstripers.com).
 * User: nivankafonseka
 * Date: 9/6/15
 * Time: 9:36 AM
 * To change this template use File | Settings | File Templates.
 */

class PostmarkMessage extends DataObject {

	private static $db = array(
		'Subject'			=> 'Varchar(200)',
		'Message'			=> 'HTMLText',
		'ToID'				=> 'Int',
		'MessageID'			=> 'Varchar(200)'

	);

	private static $has_one = array(
		'InReplyTo'			=> 'PostmarkMessage',
		'From'				=> 'PostmarkSignature',

	);

} 