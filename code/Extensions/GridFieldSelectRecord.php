<?php
/**
 * Created by Nivanka Fonseka (nivanka@silverstripers.com).
 * User: nivankafonseka
 * Date: 9/30/15
 * Time: 7:50 AM
 * To change this template use File | Settings | File Templates.
 */

class GridFieldSelectRecord implements GridField_ColumnProvider {

	public function augmentColumns($gridField, &$columns) {
		if(!in_array('Selections', $columns))
			$columns[] = 'Selections';
	}

	public function getColumnAttributes($gridField, $record, $columnName) {
		return array('class' => 'col-selection');
	}


	public function getColumnMetadata($gridField, $columnName) {
		if($columnName == 'Selections') {
			return array('title' => '<input type="checkbox" class="gird-field-select-all"> Select All');
		}
	}


	public function getColumnsHandled($gridField) {
		return array('Selections');
	}


	public function getColumnContent($gridField, $record, $columnName) {
		Requirements::javascript(POSTMARK_RELATIVE_PATH . '/javascript/GridFieldSelectRecord.js');
		$field = CheckboxField::create('gridfield_select', null);
		$field->addExtraClass('grid-field-select');
		$field->setAttribute('data-val', $record->ID);
		return $field->Field();
	}

} 