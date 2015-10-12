<?php
/**
 * Created by Nivanka Fonseka (nivanka@silverstripers.com).
 * User: nivankafonseka
 * Date: 9/30/15
 * Time: 2:30 PM
 * To change this template use File | Settings | File Templates.
 */
use Postmark\PostmarkClient;
use Postmark\Models\PostmarkAttachment;

class PostmarkMailer extends Mailer {

	private static $record_emails = false;
	private static $reply_to_message = 0;

	public static function RecordEmails($bRecord){
		self::$record_emails = $bRecord;
	}

	public static function ReplyToMessageID($reply_to_message){
		self::$reply_to_message = $reply_to_message;
	}

	public function sendPlain($to, $from, $subject, $plainContent, $attachedFiles = false, $customheaders = false) {
		return $this->sendPostmarked(
			$to,
			$from,
			$subject,
			nl2br($plainContent),
			$plainContent,
			$attachedFiles,
			$customheaders
		);

	}

	public function sendHTML($to, $from, $subject, $htmlContent, $attachedFiles = false, $customheaders = false,
							 $plainContent = false) {

		return $this->sendPostmarked(
			$to,
			$from,
			$subject,
			$htmlContent,
			$plainContent ? $plainContent : strip_tags($htmlContent),
			$attachedFiles,
			$customheaders
		);

	}


	public function sendPostmarked($to, $from, $subject, $htmlContent, $plainContent, $attachedFiles, $customheaders, $signature = null){

		if(empty($signature)){
			$signature = PostmarkSignature::get()->filter('Email', $from)->first();
		}

		$client = new PostmarkClient(SiteConfig::current_site_config()->PostmarkToken);

		$customerIDs = PostmarkHelper::client_list()->filter('Email', explode(',', $to))->column('ID');

		$message = null;
		if(is_array($customerIDs) && !empty($customerIDs) && self::$record_emails){
			$message = new PostmarkMessage(array(
				'Subject'			=> $subject,
				'Message'			=> $htmlContent,
				'PlainMessage'		=> $plainContent,
				'ToID'				=> implode(',', $customerIDs),
				'FromID'			=> $signature ? $signature->ID : $signature,
				'InReplyToID'		=> self::$reply_to_message
			));
			$message->write();
		}


		$cc = null;
		if (isset($customheaders['CC'])) { $cc = $customheaders['CC']; unset($customheaders['CC']); }
		if (isset($customheaders['cc'])) { $cc = $customheaders['cc']; unset($customheaders['cc']); }

		$bcc = null;
		if (isset($customheaders['BCC'])) { $bcc = $customheaders['BCC']; unset($customheaders['BCC']); }
		if (isset($customheaders['bcc'])) { $cc = $customheaders['bcc']; unset($customheaders['bcc']); }

		$attachments = null;
		if($attachedFiles && is_array($attachedFiles)){
			$attachments = array();
			foreach($attachedFiles as $attachment){
				$attachments[] = PostmarkAttachment::fromRawData(
					$attachment['contents'],
					$attachment['filename'],
					$attachment['mimetype']
				);

				if(is_array($customerIDs) && !empty($customerIDs) && self::$record_emails){
					$postmarkAttachment = new Attachment(array(
						'Content'				=> base64_encode($attachment['contents']),
						'FileName'				=> $attachment['filename'],
						'ContentType'			=> $attachment['mimetype'],
						'PostmarkMessageID'		=> $message->ID,
						'Length'				=> strlen($attachment['contents'])
					));
					$postmarkAttachment->write();
				}
			}
		}




		$arrangedHeaders = null;
		if($customheaders && is_array($customheaders) && count($customheaders)){
			$arrangedHeaders = array();
			foreach($customheaders as $key => $val){
				$arrangedHeaders[$key] = $val;
			}
		}

		$sendResult = $client->sendEmail(
			$from,
			$to,
			$subject,
			$htmlContent,
			$plainContent,
			null,
			true,
			$message ? $message->replyToEmailAddress() : $from,
			$cc,
			$bcc,
			$arrangedHeaders,
			$attachments
		);

		if(is_array($customerIDs) && !empty($customerIDs)  && self::$record_emails && $sendResult->__get('message') == 'OK'){
			$message->MessageID = $sendResult->__get('messageid');
			$message->write();
		}

	}

} 