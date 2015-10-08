<?php
/**
 * Created by Nivanka Fonseka (nivanka@silverstripers.com).
 * User: nivankafonseka
 * Date: 10/8/15
 * Time: 11:20 AM
 * To change this template use File | Settings | File Templates.
 */

class GridFieldCustomerReadEmailsButton implements GridField_ColumnProvider {

	public function augmentColumns($gridField, &$columns) {
		if(!in_array('Actions', $columns))
			$columns[] = 'Actions';
	}

	public function getColumnAttributes($gridField, $record, $columnName) {
		return array('class' => 'col-buttons');
	}


	public function getColumnMetadata($gridField, $columnName) {
		if($columnName == 'Actions') {
			return array('title' => '');
		}
	}


	public function getColumnsHandled($gridField) {
		return array('Actions');
	}

	public function getColumnContent($gridField, $record, $columnName) {
		Requirements::css(POSTMARK_RELATIVE_PATH . '/css/GridFieldCustomerReadEmailsButton.css');
		$link = Director::baseURL() . 'admin/messages?q[Search]=' . $record->Email;
		return <<<HTML
<a class="action action-detail read-more-link" href="$link" title="<% _t('GridFieldEditButton_ss.EDIT', 'Edit') %>"></a>

HTML;

	}

} 