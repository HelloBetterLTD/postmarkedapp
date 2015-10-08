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
		'PlainMessage'		=> 'Text',
		'ToID'				=> 'Text',
		'FromCustomerID'	=> 'Int',
		'MessageID'			=> 'Varchar(100)',

		// user hash and message hash are use to keep the thread going.
		'UserHash'			=> 'Varchar(100)',
		'MessageHash'		=> 'Varchar(100)',
		'Read'				=> 'Boolean'
	);

	private static $has_one = array(
		'InReplyTo'			=> 'PostmarkMessage',
		'From'				=> 'PostmarkSignature',
	);

	private static $has_many = array(
		'Attachments'		=> 'Attachment'
	);

	private static $summary_fields = array(
		'On'				=> 'LastUpdateTime',
		'Subject',
		'From'				=> 'FromEmail',
		'To'
	);

	private static $casting = array(
		'getLastUpdateTime'	=> 'HTMLVarchar'
	);


	public function getLastUpdateTime(){
		$lastEdited = $this->dbObject('LastEdited');
		return $lastEdited->Ago(true);
	}


	public function onBeforeDelete(){
		$children = PostmarkMessage::get()->filter('InReplyToID', $this->ID);
		foreach($children as $child){
			$child->delete();
		}

		parent::onBeforeDelete();
	}


	public function onAfterWrite(){
		parent::onAfterWrite();
		if(empty($this->UserHash) || empty($this->MessageHash)){
			$this->UserHash = $this->makeUserHash();
			$this->MessageHash = $this->makeMessageHash();

			$rootMessage = $this->getRootMessage();
			if($rootMessage->ID == $this->ID){
				$this->LastEdited = SS_Datetime::now()->getValue();
			}
			else{
				$rootMessage->LastEdited = SS_Datetime::now()->getValue();
				$rootMessage->write();
			}

			$this->write();
		}
	}


	public function getCMSFields(){
		$fields = parent::getCMSFields();

		$fields->removeByName('Attachments');

		return $fields;
	}

	public function ShowReplyButton(){
		return $this->FromCustomerID != 0;
	}

	public function ReplyFromID(){
		if($rootMessage = $this->getRootMessage()){
			return $rootMessage->From;
		}
	}

	public function ReplyToID(){
		return $this->FromCustomerID;
	}

	public function ReplyToSubject(){
		$strRet = $this->Subject;
		if(strpos($strRet, 'RE :') != 0){
			$strRet = 'RE: ' . $strRet;
		}
		return $strRet;
	}

	public function getTitle(){
		return $this->Subject;
	}

	public function getRootMessage(){
		if($this->InReplyToID == 0){
			return $this;
		}
		else if($item = PostmarkMessage::get()->byID($this->InReplyToID)){
			return $item->getRootMessage();
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

	public function getSummaryLine(){
		$strContents = strip_tags($this->Message);
		$strContents = str_replace("\n", " ", $strContents);
		return substr($strContents, 0, 40);
	}

	public function getFromTitle(){
		if($this->FromCustomerID){
			if($customer = PostmarkHelper::find_client($this->FromCustomerID)){
				return $customer->getTitle();
			}
		}
		else if($this->FromID){
			return $this->From()->getTitle();
		}
	}

	public function getThread($alRet = null){
		if(!$alRet){
			$alRet = new ArrayList();
			$alRet->push($this);
		}

		$children = PostmarkMessage::get()->filter('InReplyToID', $this->ID)->sort('ID');
		foreach($children as $child){
			$alRet->push($child);
			$child->getThread($alRet);
		}

		return $alRet;
	}

	public function MessagePopupLink(){
		return Director::baseURL() . 'admin/messages/PostmarkMessage/MessagePopupContents?Subject=' . $this->ReplyToSubject()
			. '&FromID=' . $this->ReplyFromID()
			. '&ToID=' . $this->ReplyToID()
			. '&ReplyToMessageID=' . $this->ID;
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

	public function Children(){
		return PostmarkMessage::get()->filter('InReplyToID', $this->ID);
	}

	public function hasUnreadMessage(){
		if(!$this->Read && $this->FromCustomerID != 0){
			return true;
		}
		foreach($this->Children() as $child){
			if($child->hasUnreadMessage()){
				return true;
			}
		}
		return false;
	}

	public function MessageCounter(&$count, $bUnread = false){
		if(!$bUnread){
			$count += 1;
		}
		else if(!$this->Read && $this->FromCustomerID != 0){
			$count += 1;
		}
		foreach($this->Children() as $child){
			$child->MessageCounter($count);
		}
	}

	public function CountMessages(){
		$iCount = 0;
		$this->MessageCounter($iCount);
		return $iCount;
	}

	public function CountUnreadMessage(){
		$iCount = 0;
		$this->MessageCounter($iCount, true);
		return $iCount;
	}

	public function getCountString(){
		return $this->CountUnreadMessage() . '/' . $this->CountMessages();
	}

	public function updateAsRead(){
		$this->Read = 1;
		$this->write();
	}

} 