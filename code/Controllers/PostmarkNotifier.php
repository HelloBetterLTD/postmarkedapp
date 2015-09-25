<?php
/**
 * Created by Nivanka Fonseka (nivanka@silverstripers.com).
 * User: nivankafonseka
 * Date: 9/24/15
 * Time: 5:00 PM
 * To change this template use File | Settings | File Templates.
 */


class PostmarkNotifier extends Controller {

	public function index(){

		$this->recordInbound();

	}


	function recordInbound(){

		/*
		$strFile = BASE_PATH . '/tmp.json';
		$json = file_get_contents("php://input");
		file_put_contents($strFile, $json);
		die();
		*/


		$strJson = file_get_contents("php://input");

		//$strJson = file_get_contents($strFile);

		try {
			$arrResponse = Convert::json2array($strJson);

			if($savedMessage = PostmarkMessage::get()->filter('MessageID', $arrResponse['MessageID'])->first()){
				return;
			}

			$hash = $arrResponse['ToFull'][0]['MailboxHash'];
			$hashParts = explode('+', $hash);
			$lastMessage = PostmarkMessage::get()->filter(array(
				'UserHash'			=> $hashParts[0],
				'MessageHash'		=> $hashParts[1]
			))->first();

			if($lastMessage){

				$fromCustomer = PostmarkHelper::find_client_by_email($arrResponse['From']);

				$message = new PostmarkMessage(array(
					'Subject'			=> $arrResponse['Subject'],
					'Message'			=> $arrResponse['HtmlBody'],
					'ToID'				=> 0,
					'MessageID'			=> $arrResponse['MessageID'],
					'InReplyToID'		=> $lastMessage->ID,
					'FromCustomerID'	=> $fromCustomer ? $fromCustomer->ID : 0
				));
				$message->write();

				if(isset($arrResponse['Attachments']) && count($arrResponse['Attachments'])){
					foreach($arrResponse['Attachments'] as $attachment){
						$attachmentObject = new Attachment(array(
							'Content'				=> $attachment['Content'],
							'FileName'				=> $attachment['Name'],
							'ContentType'			=> $attachment['ContentType'],
							'Length'				=> $attachment['ContentLength'],
							'ContentID'				=> $attachment['ContentID'],
							'PostmarkMessageID'		=> $message->ID
						));
						$attachmentObject->write();
					}
				}

			}

		}catch(Exception $e){ }

		return 'OK';
	}

} 