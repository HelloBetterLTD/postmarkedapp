<?php
/**
 * Created by Nivanka Fonseka (nivanka@silverstripers.com).
 * User: nivankafonseka
 * Date: 9/25/15
 * Time: 11:18 AM
 * To change this template use File | Settings | File Templates.
 */

class ObjectSelectorField extends FormField {

	private static $allowed_actions = array(
		'find'
	);

	private $valueArray = array();
	private $sourceObject = '';
	private $displayField = '';

	public function Field($properties = array()) {
		Requirements::javascript('silverstripe-postmarked/javascript/ObjectSelectorField.js');
		return parent::Field($properties);
	}

	function setDisplayField($field){
		$this->displayField = $field;
		return $this;
	}

	function setSourceObject($sourceObject){
		$this->sourceObject = $sourceObject;
		return $this;
	}

	function setValue($value){
		if(!is_array($value)){
			$this->valueArray = explode(',', $value);
		}
		return parent::setValue($value);
	}

	function SelectedValues(){
		if($this->sourceObject && $this->valueArray){
			$list = DataList::create($this->sourceObject)->filter(array(
				'ID'	=> $this->valueArray
			));
			$alRet = new ArrayList();
			foreach($list as $item){
				$alRet->push(new ArrayData(array(
					'Title'		=> $item->getField($this->displayField),
					'ID'		=> $item->ID
				)));
			}
			return $alRet;
		}
	}

	public function dataValue() {
		if($this->valueArray && is_array($this->valueArray)) {
			$filtered = array();
			foreach($this->valueArray as $item) {
				if($item) {
					$filtered[] = str_replace(",", "{comma}", $item);
				}
			}

			return implode(',', $filtered);
		}

		return '';
	}

	function find(){
		$arr = array();
		if(isset($_GET['filter']) && $this->sourceObject){
			$list = DataList::create($this->sourceObject)->filter($this->displayField . ':PartialMatch', $_GET['filter']);
			foreach($list as $item){
				$arr[$item->ID]  = $item->getField($this->displayField);
			}
		}
		return Convert::array2json($arr);
	}
} 