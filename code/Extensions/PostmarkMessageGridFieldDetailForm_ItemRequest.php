<?php
/**
 * Created by Nivanka Fonseka (nivanka@silverstripers.com).
 * User: nivankafonseka
 * Date: 9/25/15
 * Time: 3:52 PM
 * To change this template use File | Settings | File Templates.
 */

class PostmarkMessageGridFieldDetailForm_ItemRequest extends GridFieldDetailForm_ItemRequest {

	public function ItemEditForm() {
		Requirements::css('silverstripe-postmarked/css/messages.css');
		$form = parent::ItemEditForm();
		$form->setTemplate('PostmarkEditorTemplate');
		return $form;
	}

	public function getRecord(){
		return $this->record;
	}

} 