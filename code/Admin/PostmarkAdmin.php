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
		'PostmarkMessage',
		'CustomerTag',
		'CustomerStatus'
	);

	private static $allowed_actions = array(
		'MessageForm'
	);



	public function MessageForm($request = null, $itemID = 0){
		if($itemID == 0){
			$itemID = $_REQUEST['ToMemberID'];
		}
		$form = new Form(
			$this,
			'MessageForm',
			new FieldList(array(
				DropdownField::create('FromID', 'From')->setSource(PostmarkSignature::get()->filter('IsActive', 1)->map('ID', 'Email')->toArray()),
				TextField::create('Subject'),
				TextareaField::create('Body'),
				HiddenField::create('ToMemberID')->setValue($itemID)
			)),
			new FieldList(FormAction::create('postmessage', 'Sent Message')
		));

		$form->setFormAction($this->Link('PostmarkMessage/MessageForm'));

		return $form;

	}

	public function postmessage($data, $form){

		$client = new PostmarkClient(SiteConfig::current_site_config()->PostmarkToken);

		$member = DataList::create(Config::inst()->get('PostmarkAdmin', 'member_class'))->byID($data['ToMemberID']);
		$signature = PostmarkSignature::get()->byID($data['FromID']);

		$sendResult = $client->sendEmail(
			$signature->Email,
			$member->Email,
			$data['Subject'],
			$data['Body']
		);

		if($sendResult->__get('message') == 'OK'){
			$message = new PostmarkMessage(array(
				'Subject'			=> $data['Subject'],
				'Message'			=> $data['Body'],
				'ToID'				=> $data['ToMemberID'],
				'MessageID'			=> $sendResult->__get('messageid'),
				'FromID'			=> $signature->ID,
			));
			$message->write();
		}

	}

} 