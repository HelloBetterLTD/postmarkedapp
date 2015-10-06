<?php
/**
 * Created by Nivanka Fonseka (nivanka@silverstripers.com).
 * User: nivankafonseka
 * Date: 9/6/15
 * Time: 9:25 AM
 * To change this template use File | Settings | File Templates.
 */

use Postmark\PostmarkClient;

class PostmarkAdmin extends ModelAdmin {

	private static $url_segment = 'messages';
	private static $menu_title = 'Messages';
	private static $menu_icon = '/silverstripe-postmarked/images/icons/post.png';

	private static $managed_models = array(
		'PostmarkMessage'
	);

	private static $allowed_actions = array(
		'MessageForm',
		'MessagePopupContents'
	);

	public function init() {
		parent::init();
		$this->showImportForm = false;
		Requirements::css(POSTMARK_RELATIVE_PATH . '/css/icons.css');
	}

	public function getEditForm($id = null, $fields = null){
		$form = parent::getEditForm($id = null, $fields = null);

		if($this->modelClass == 'PostmarkMessage'){
			$fields = $form->Fields();
			$grid = $fields->dataFieldByName($this->sanitiseClassName($this->modelClass));
			if($grid){
				$configs = $grid->getConfig();
				$configs->removeComponentsByType('GridFieldAddNewButton');
				$configs->removeComponentsByType('GridFieldExportButton');
				$configs->removeComponentsByType('GridFieldPrintButton');

				$editForm = $configs->getComponentByType('GridFieldDetailForm');
				$editForm->setItemRequestClass('PostmarkMessageGridFieldDetailForm_ItemRequest');

			}

		}

		return $form;
	}


	public function getList(){
		$list = parent::getList();
		if($this->modelClass == 'PostmarkMessage'){
			$list = $list->filter('InReplyToID', 0)->sort('LastEdited DESC');
		}
		return $list;
	}




	public function MessageForm($request = null, $itemID = 0){
		if($itemID == 0){
			$itemID = isset($_REQUEST['ToMemberID']) ? $_REQUEST['ToMemberID'] : 0;
		}
		$form = new Form(
			$this,
			'MessageForm',
			new FieldList(array(
				ObjectSelectorField::create('ToMemberID', 'To:')->setValue($itemID)->setSourceObject(Config::inst()->get('PostmarkAdmin', 'member_class'))->setDisplayField('Email'),
				DropdownField::create('FromID', 'From')->setSource(PostmarkSignature::get()->filter('IsActive', 1)->map('ID', 'Email')->toArray()),
				TextField::create('Subject'),
				QuillEditorField::create('Body'),
				HiddenField::create('InReplyToID')
			)),
			new FieldList(FormAction::create('postmessage', 'Sent Message')
		));

		$requiredField = new RequiredFields(array(
			'FromID',
			'Subject',
			'Body'
		));
		$form->setValidator($requiredField);

		$this->extend('updateMessageForm', $form, $itemID);

		$form->setFormAction($this->Link('PostmarkMessage/MessageForm'));
		return $form;

	}

	public function MessagePopupContents(){
		$form = $this->MessageForm();

		$form->Fields()->dataFieldByName('Subject')->setValue($_GET['Subject']);
		$form->Fields()->dataFieldByName('FromID')->setValue($_GET['FromID']);
		$form->Fields()->dataFieldByName('ToMemberID')->setValue(array(
			$_GET['ToID']
		));

		return $form->forTemplate();
	}

	public function postmessage($data, $form){

		$signature = PostmarkSignature::get()->byID($data['FromID']);
		$arrEmails = PostmarkHelper::find_client_emails($data['ToMemberID']);
		PostmarkMailer::RecordEmails(true);

		$email = new Email(
			$signature->Email,
			implode(',', $arrEmails),
			$data['Subject'],
			$data['Body']
		);


		$this->extend('updatePostmessage', $email, $data);


		$email->send();

		PostmarkMailer::RecordEmails(false);


	}

} 