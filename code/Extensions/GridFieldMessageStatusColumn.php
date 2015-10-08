<?php
/**
 * Created by Nivanka Fonseka (nivanka@silverstripers.com).
 * User: nivankafonseka
 * Date: 10/8/15
 * Time: 1:23 PM
 * To change this template use File | Settings | File Templates.
 */

class GridFieldMessageStatusColumn implements GridField_ColumnProvider {

	public function augmentColumns($gridField, &$columns) {
		if(!in_array('Status', $columns))
			$columns[] = 'Status';
	}

	public function getColumnAttributes($gridField, $record, $columnName) {
		return array('class' => 'col-message-status');
	}


	public function getColumnMetadata($gridField, $columnName) {
		if($columnName == 'Status') {
			return array('title' => '');
		}
	}


	public function getColumnsHandled($gridField) {
		return array('Status');
	}

	public function getColumnContent($gridField, $record, $columnName) {
		Requirements::css(POSTMARK_RELATIVE_PATH . '/css/GridFieldMessageStatusColumn.css');
		Requirements::javascript(POSTMARK_RELATIVE_PATH . '/javascript/GridFieldMessageStatusColumn.js');

		$strIcon = 'icon-envelope2 opened';
		if($record->hasUnreadMessage()){
			$strIcon = 'icon-envelope unread-message';
		}

		return '<span class="action thread-detail"><span class="message-status-icon ' . $strIcon . '"></span>' . $record->getCountString() . '</span>';

	}

} 