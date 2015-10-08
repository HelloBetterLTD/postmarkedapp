<?php
/**
 * Created by Nivanka Fonseka (nivanka@silverstripers.com).
 * User: nivankafonseka
 * Date: 9/6/15
 * Time: 9:25 AM
 * To change this template use File | Settings | File Templates.
 */

class CRMAdmin extends ModelAdmin {

	private static $url_segment = 'crm';
	private static $menu_title = 'CRM';
	private static $menu_icon = '/silverstripe-postmarked/images/icons/crm.png';

	private static $managed_models = array(
		'CustomerTag',
		'CustomerStatus'
	);

	public function init(){
		parent::init();
		Requirements::css(POSTMARK_RELATIVE_PATH . '/css/icons.css');
	}

	function getEditForm($id = null, $fields = null){

		$form = parent::getEditForm($id, $fields);

		Requirements::javascript('silverstripe-postmarked/javascript/PostmarkMessageButton.js');

		if($this->modelClass == Config::inst()->get('PostmarkAdmin', 'member_class')){
			$fields = $form->Fields();
			$grid = $fields->dataFieldByName($this->sanitiseClassName($this->modelClass));
			if($grid){

				$configs = $grid->getConfig();
				$configs->addComponent(new GridFieldPostmarkMessageButton());
				$configs->addComponent(new GridFieldSelectRecord(), 'GridFieldDataColumns');
				$configs->addComponent($tags = new GridFieldManageBulkRelationships('before'), 'GridFieldAddNewButton');
				$tags->setFromClass($this->modelClass)->setRelationship('Tags')->setTitle('Tags');


				$configs->addComponent($status = new GridFieldManageBulkRelationships('before'), 'GridFieldAddNewButton');
				$status->setFromClass($this->modelClass)->setRelationship('Statuses')->setTitle('Status');

				$columns = $configs->getComponentByType('GridFieldDataColumns');
				$columns->setDisplayFields(array(
					'getFullName'			=> 'Name',
					'Email'					=> 'Email',
					'Company'				=> 'Company',
					'getTagCollection'		=> 'Tags',
					'getStatusCollection'	=> 'Status',
					'getNotifications'		=> 'Notifications'
				));

				$configs->removeComponentsByType('GridFieldExportButton');
				$configs->removeComponentsByType('GridFieldPrintButton');

			}
		}

		$this->extend('updateCRMEditorForm', $form, $this->modelClass);

		return $form;

	}

	public function getSearchContext(){
		$customerClass = Config::inst()->get('PostmarkAdmin', 'member_class');
		if($this->modelClass == $customerClass){
			$context = new CustomerSearchContext($customerClass);
			foreach($context->getFields() as $field){

				if(isset($_REQUEST['q']) && isset($_REQUEST['q'][$field->getName()])){
					$field->setValue($_REQUEST['q'][$field->getName()]);
				}
				$field->setName(sprintf('q[%s]', $field->getName()));
			}
			foreach($context->getFilters() as $filter){
				$filter->setFullName(sprintf('q[%s]', $filter->getFullName()));
			}
			return $context;
		}
		return parent::getSearchContext();
	}





} 