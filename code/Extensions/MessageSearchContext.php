<?php
/**
 * Created by Nivanka Fonseka (nivanka@silverstripers.com).
 * User: nivankafonseka
 * Date: 10/7/15
 * Time: 4:17 PM
 * To change this template use File | Settings | File Templates.
 */

class MessageSearchContext extends SearchContext
{


    public function __construct($modelClass, $fields = null, $filters = null)
    {
        $fields = new FieldList(
            TextField::create('Search')->setAttribute('placeholder', 'Email, Subject, Name')
        );

        $filters = array();
        parent::__construct($modelClass, $fields, $filters);
    }


    public function getQuery($searchParams, $sort = false, $limit = false, $existingQuery = null)
    {
        $dataList = parent::getQuery($searchParams, $sort, $limit, $existingQuery);
        $params = is_object($searchParams) ? $searchParams->getVars() : $searchParams;
        $query = $dataList->dataQuery();
        if (!is_object($searchParams)) {
            if (isset($params['Search']) && !empty($params['Search'])) {
                $customerClass = Config::inst()->get('PostmarkAdmin', 'member_class');
                $strFilter = "'%" . Convert::raw2sql($params['Search']) . "%'";
                $query->where("
					Subject LIKE $strFilter
					OR Message LIKE $strFilter
					OR FromCustomerID IN ( SELECT ID FROM $customerClass WHERE
					 	FirstName LIKE $strFilter
					 	OR Surname LIKE $strFilter
					 	OR Email LIKE $strFilter
				 	)
				 	OR ToID IN ( SELECT ID FROM $customerClass WHERE
					 	FirstName LIKE $strFilter
					 	OR Surname LIKE $strFilter
					 	OR Email LIKE $strFilter
				 	)

				");
            }
        }

        return $dataList->setDataQuery($query);
    }
}
