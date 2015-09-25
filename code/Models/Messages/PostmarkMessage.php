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
		'Subject'			=> 'Varchar(500)',
		'Message'			=> 'HTMLText',
		'ToID'				=> 'Text',
		'FromCustomerID'	=> 'Int',
		'MessageID'			=> 'Varchar(100)',

		// user hash and message hash are use to keep the thread going.
		'UserHash'			=> 'Varchar(100)',
		'MessageHash'		=> 'Varchar(100)'
	);

	private static $has_one = array(
		'InReplyTo'			=> 'PostmarkMessage',
		'From'				=> 'PostmarkSignature',
	);

	private static $has_many = array(
		'Attachments'		=> 'Attachment'
	);

	private static $summary_fields = array(
		'Subject',
		'From'	=> 'FromEmail',
		'To'
	);


	public function onBeforeDelete(){
		$children = PostmarkMessage::get()->filter('InReplyToID', $this->ID);
		foreach($children as $child){
			$child->delete();
		}
	}


	public function onAfterWrite(){
		parent::onAfterWrite();
		if(empty($this->UserHash) || empty($this->MessageHash)){
			$this->UserHash = $this->makeUserHash();
			$this->MessageHash = $this->makeMessageHash();
			$this->write();
		}
	}

	public function getFromEmail(){
		if($this->FromCustomerID){
			if($customer = PostmarkHelper::find_client($this->FromCustomerID)){
				return $customer->Email;
			}
		}
		else if($this->FromID){
			return $this->From()->Email;
		}
	}

	public function getTo(){
		$arrEmails = array();
		$arrIDs = explode(',', $this->ToID);
		foreach($arrIDs as $iID){
			if($member = PostmarkHelper::find_client($iID)){
				$arrEmails[] = $member->Email;
			}
		}
		return implode(', ', $arrEmails);
	}

	public function makeUserHash(){
		return md5($this->ToID);
	}

	public function makeMessageHash(){
		return md5($this->ID);
	}


	// reply+userIDthreadID@yourapp.com
	public function replyToEmailAddress(){
		if($strInboundEmail = SiteConfig::current_site_config()->InboundEmail){
			$arrParts = explode('@', $strInboundEmail);
			if(count($arrParts) == 2){
				return $arrParts[0] . '+' . $this->makeUserHash() . '+' . $this->makeMessageHash() . '@' . $arrParts[1];
			}
			else{
				user_error('Inbound address doesnt look correct', 'error');
			}
		}
		return null;
	}

	public function getMessage(){
		$dbValue = $this->getField('Message');

		$strRet = PostmarkHelper::update_attachments($dbValue);

		return $strRet;

	}

} 