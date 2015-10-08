<?php
/**
 * Created by Nivanka Fonseka (nivanka@silverstripers.com).
 * User: nivankafonseka
 * Date: 9/30/15
 * Time: 8:09 AM
 * To change this template use File | Settings | File Templates.
 */

class GridFieldManageBulkRelationships
	implements GridField_HTMLProvider, GridField_ActionProvider, GridField_DataManipulator, GridField_URLHandler {

	protected $fromClass;
	protected $relationship;
	protected $targetFragment;
	protected $title;

	protected $buttonName;

	public function setFromClass($class){
		$this->fromClass = $class;
		return $this;
	}

	public function setRelationship($relationship){
		$this->relationship = $relationship;
		return $this;
	}

	public function getSourceObject(){
		$object = singleton($this->fromClass);
		if($hasOne = $object->has_one()){
			if(array_key_exists($this->relationship, $hasOne)){
				return $hasOne[$this->relationship];
			}
		}
		if($hasMany = $object->has_many()){
			if(array_key_exists($this->relationship, $hasMany)){
				return $hasMany[$this->relationship];
			}
		}
		if($manyMany = $object->many_many()){
			if(array_key_exists($this->relationship, $manyMany)){
				return $manyMany[$this->relationship];
			}
		}
	}

	public function getRelationshipType(){
		$object = singleton($this->fromClass);
		if($hasOne = $object->has_one()){
			if(array_key_exists($this->relationship, $hasOne)){
				return 'has_one';
			}
		}
		if($hasMany = $object->has_many()){
			if(array_key_exists($this->relationship, $hasMany)){
				return 'has_many';
			}
		}
		if($manyMany = $object->many_many()){
			if(array_key_exists($this->relationship, $manyMany)){
				return 'many_many';
			}
		}
	}


	public function setButtonName($name) {
		$this->buttonName = $name;
		return $this;
	}

	public function setTitle($title) {
		$this->title = $title;
		return $this;
	}


	public function __construct($targetFragment = 'before') {
		$this->targetFragment = $targetFragment;
	}

	public function getHTMLFragments($gridField) {
		$singleton = singleton($gridField->getModelClass());
		if(!$singleton->canCreate()) {
			return array();
		}

		Requirements::javascript(POSTMARK_RELATIVE_PATH . '/javascript/GridFieldManageBulkRelationships.js');
		Requirements::css(POSTMARK_RELATIVE_PATH . '/css/GridFieldManageBulkRelationships.css');
		if(!$this->buttonName) {
			$this->buttonName = _t('CRMAdmin.Add', 'Add');
		}

		$sourceObject = $this->getSourceObject();
		$data = new ArrayData(array(
			'AddLink' 				=> $gridField->Link('addtorelationship-' . $this->relationship),
			'RemoveLink' 			=> $gridField->Link('removefromrelationship-' . $this->relationship),
			'ButtonName' 			=> $this->buttonName,
			'ObjectSelectorField'	=> ObjectSelectorField::create('relation_selector', null)->setCustomLink(true)->setSourceObject($sourceObject)->Field(),
			'FromClass'				=> $this->fromClass,
			'Relationship'			=> $this->relationship,
			'Title'					=> $this->title
		));
		return array(
			$this->targetFragment => $data->renderWith('GridFieldManageBulkRelationships'),
		);
	}

	public function getActions($gridField){
		return array(
			'addtorelationship-' . $this->relationship,
			'removefromrelationship-' . $this->relationship
		);
	}

	public function getURLHandlers($gridField) {
		return array(
			'addtorelationship-' . $this->relationship 			=> 'doAddToRelationship',
			'removefromrelationship-' . $this->relationship 	=> 'doRemoveToRelationship',
		);
	}

	public function handleAction(GridField $gridField, $actionName, $arguments, $data) {
		$item = $gridField->getList()->byID($arguments['RecordID']);
		if(!$item) {
			return;
		}
	}

	public function getManipulatedData(GridField $gridField, SS_List $dataList) {
		return $dataList;
	}

	public function doRemoveToRelationship($gridField, $request){
		$items = $request->postVar('items');
		$related = $request->postVar('related');

		$dlList = DataList::create($gridField->getModelClass());
		$dlList = $dlList->filter('ID', $items);

		$dlRelated = DataList::create($this->getSourceObject());
		$dlRelated = $dlRelated->filter('ID', $related);

		$strRelType = $this->getRelationshipType();

		foreach($dlList as $item){
			foreach($dlRelated as $relatedItem){
				if($strRelType == 'has_one'){
					$item->setField($this->relationship . 'ID', 0);
					$item->write();
				}
				else if ($strRelType == 'has_many'){
					$relationList = $item->getComponents($this->relationship);
					$relationList->remove($relatedItem);
				}
				else if($strRelType == 'many_many'){
					$relationList = $item->getManyManyComponents($this->relationship);
					$relationList->remove($relatedItem);
				}
			}
		}

	}

	public function doAddToRelationship($gridField, $request){
		$items = $request->postVar('items');
		$related = $request->postVar('related');

		$dlList = DataList::create($gridField->getModelClass());
		$dlList = $dlList->filter('ID', $items);

		$dlRelated = DataList::create($this->getSourceObject());
		$dlRelated = $dlRelated->filter('ID', $related);

		$strRelType = $this->getRelationshipType();

		foreach($dlList as $item){
			foreach($dlRelated as $relatedItem){
				if($strRelType == 'has_one'){
					$item->setField($this->relationship . 'ID', $relatedItem->ID);
					$item->write();
				}
				else if ($strRelType == 'has_many'){
					$relationList = $item->getComponents($this->relationship);
					$relationList->add($relatedItem);
				}
				else if($strRelType == 'many_many'){
					$relationList = $item->getManyManyComponents($this->relationship);
					$relationList->add($relatedItem);
				}
			}
		}

	}

} 