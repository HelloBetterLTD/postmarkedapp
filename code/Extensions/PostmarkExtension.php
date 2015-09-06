<?php
/**
 * Created by Nivanka Fonseka (nivanka@silverstripers.com).
 * User: nivankafonseka
 * Date: 9/6/15
 * Time: 9:05 AM
 * To change this template use File | Settings | File Templates.
 */

class PostmarkExtension extends DataExtension {

	private static $db = array(
		'PostmarkToken'			=> 'Varchar(100)',
	);

	public function updateCMSFields(FieldList $fields){

		$fields->addFieldsToTab('Root.Settings.Postmark', array(
			TextField::create('PostmarkToken'),
			GridField::create('Signatures', 'Signatures')->setList(PostmarkSignature::get())->setConfig(new GridFieldConfig_RecordEditor(20))
		));

	}

} 