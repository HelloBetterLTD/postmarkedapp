<?php
/**
 * Created by Nivanka Fonseka (nivanka@silverstripers.com).
 * User: nivankafonseka
 * Date: 9/24/15
 * Time: 5:37 PM
 * To change this template use File | Settings | File Templates.
 */

class Attachment extends DataObject {

	private static $db = array(
		'ExternalLink'			=> 'Varchar(500)',
		'Content'				=> 'BLOBField',
		'FileName'				=> 'Varchar(500)',
		'ContentType'			=> 'Varchar(100)',
		'Length'				=> 'Int',
		'ContentID'				=> 'Varchar(100)',
	);

	private static $has_one = array(
		'PostmarkMessage'		=> 'PostmarkMessage',
		'File'					=> 'File'
	);

	public function returnToBrowser(){
		if($this->ExternalLink){
			return $this->ExternalLink;
		}
		else if($this->FileID){
			if($file = $this->File()){
				return $file->AbsoluteURL();
			}
		}
		else {
			$content = base64_decode(chunk_split($this->Content));
			$response = new SS_HTTPResponse($content, '200');
			$response->addHeader('Content-Description', 'File Transfer');
			$response->addHeader('Content-Type', $this->ContentType);
			if($this->IsImage()){
				$response->addHeader('Content-Disposition', 'inline; filename="'.basename($this->FileName) . '"');
			}
			else{
				$response->addHeader('Content-Disposition', 'download; filename="'.basename($this->FileName) . '"');
			}
			$response->addHeader('Content-Length', $this->Length);
			$response->output();
		}
	}

	public function IsImage(){
		return strpos($this->ContentType, 'image') !== false;
	}

	public function Link(){
		return Director::absoluteBaseURL() . 'postmark-attachments/a/' . $this->ID;
	}

	public function getTitle(){
		return $this->FileName;
	}

	function getExtension(){
		return pathinfo($this->FileName, PATHINFO_EXTENSION);
	}

	public function Icon() {
		$ext = strtolower($this->getExtension());
		if(Director::fileExists(POSTMARK_PATH . "/images/icons/files/{$ext}_32.gif")) {
			return POSTMARK_RELATIVE_PATH . "/images/icons/files/{$ext}_32.gif";
		}

		return POSTMARK_RELATIVE_PATH . "/images/icons/files/generic_32.gif";
	}

} 