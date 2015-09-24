<?php
/**
 * Created by Nivanka Fonseka (nivanka@silverstripers.com).
 * User: nivankafonseka
 * Date: 9/24/15
 * Time: 6:58 PM
 * To change this template use File | Settings | File Templates.
 */

class PostmarkMySQLDatabase extends MySQLDatabase {


	public function blob($values){
		//For reference, this is what typically gets passed to this function:
		//$parts=Array('datatype'=>'mediumtext', 'character set'=>'utf8', 'collate'=>'utf8_general_ci');
		//DB::requireField($this->tableName, $this->name, "mediumtext character set utf8 collate utf8_general_ci");

		return 'blob';
	}

} 