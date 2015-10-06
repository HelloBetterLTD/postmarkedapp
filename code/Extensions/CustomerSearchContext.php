<?php
/**
 * Created by Nivanka Fonseka (nivanka@silverstripers.com).
 * User: nivankafonseka
 * Date: 10/6/15
 * Time: 2:12 PM
 * To change this template use File | Settings | File Templates.
 */

class CustomerSearchContext extends SearchContext {

	public function __construct($modelClass, $fields = null, $filters = null) {


		$filters = array(
			'Email'				=> new PartialMatchFilter('Email'),
			'Company'			=> new PartialMatchFilter('Company')
		);

		parent::__construct($modelClass, null, $filters);
	}

	function getSearchFields(){
		$fields = new FieldList(
			TextField::create('Name'),
			TextField::create('Email'),
			TextField::create('Company'),
			CheckboxSetField::create('Status')->setSource(CustomerStatus::get()->map()->toArray()),
			CheckboxSetField::create('Tags')->setSource(CustomerTag::get()->map()->toArray())
		);

		$this->extend('updateCustomerSearchFields', $fields);

		return $fields;
	}

	public function getQuery($searchParams, $sort = false, $limit = false, $existingQuery = null) {

		$dataList = parent::getQuery($searchParams, $sort, $limit, $existingQuery);

		$params = is_object($searchParams) ? $searchParams->getVars() : $searchParams;

		$query = $dataList->dataQuery();

		if(!is_object($searchParams)){

			if(isset($params['Name']) && !empty($params['Name'])){
				$query->where('"FirstName" LIKE \'%' . Convert::raw2sql($params['Name']) . '%\' OR "Surname" LIKE \'%' . Convert::raw2sql($params['Name']) . '%\'');
			}


			if(isset($params['Status'])){
				$query->where('EXISTS ( SELECT 1 FROM "' . $this->modelClass . '_Statuses"
					WHERE "' . $this->modelClass . 'ID" = "' . $this->modelClass . '"."ID"
					AND "' . $this->modelClass . '_Statuses"."CustomerStatusID" IN (' . implode(',', $params['Status']) . ')
				)');
			}

			if(isset($params['Tags'])){
				$query->where('EXISTS ( SELECT 1 FROM "' . $this->modelClass . '_Tags"
					WHERE "' . $this->modelClass . 'ID" = "' . $this->modelClass . '"."ID"
					AND "' . $this->modelClass . '_Tags"."CustomerTagID" IN (' . implode(',', $params['Tags']) . ')
				)');
			}

			$this->extend('updateGetQuery', $query, $params);

		}
		return $dataList->setDataQuery($query);
	}

} 