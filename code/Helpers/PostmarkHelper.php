<?php
/**
 * Created by Nivanka Fonseka (nivanka@silverstripers.com).
 * User: nivankafonseka
 * Date: 9/24/15
 * Time: 3:48 PM
 * To change this template use File | Settings | File Templates.
 */

class PostmarkHelper extends Object {

	public static function find_client($id){
		return DataList::create(Config::inst()->get('PostmarkAdmin', 'member_class'))->byId($id);
	}

	public static function find_client_by_email($email){
		return DataList::create(Config::inst()->get('PostmarkAdmin', 'member_class'))->filter('Email', $email)->first();
	}

	public static function update_attachments($strContent){

		$strContent = preg_replace_callback('/src\s*=\s*"cid[^"]*"/mi', function($matches){
			$strURL = $matches[0];
			$strURL = str_replace('src=', '', $strURL);
			$strURL = str_replace('"', '', $strURL);
			$strURL = str_replace('\'', '', $strURL);
			$strURL = str_replace('cid:', '', $strURL);

			return 'src="' . Director::absoluteBaseURL() . 'postmark-attachments/a/' . $strURL . '"';

		}, $strContent);

		return $strContent;
	}

} 