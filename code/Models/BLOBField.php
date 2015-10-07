<?php
/**
 * Created by Nivanka Fonseka (nivanka@silverstripers.com).
 * User: nivankafonseka
 * Date: 9/24/15
 * Time: 6:53 PM
 * To change this template use File | Settings | File Templates.
 */

class BLOBField extends DBField {

	public function requireField() {
		$parts = array(
			'datatype' 		=> 'longblob',
			'arrayValue' 	=> $this->arrayValue
		);

		$values= array(
			'type' => 'blob',
			'parts' => $parts
		);

		DB::requireField($this->tableName, $this->name, $values, $this->default);
	}

} 