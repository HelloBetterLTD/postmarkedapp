<?php
/**
 * Created by Nivanka Fonseka (nivanka@silverstripers.com).
 * User: nivankafonseka
 * Date: 12/10/15
 * Time: 4:07 PM
 * To change this template use File | Settings | File Templates.
 */

class PostmarkMySQLSchemaManager extends MySQLSchemaManager {

	/**
	 * Identifier for this schema, used for configuring schema-specific table
	 * creation options
	 */
	const ID = 'PostmarkMySQLDatabase';


	public function blob($values){
		return 'blob';
	}

	public function longblob($values){
		return 'longblob';
	}

} 