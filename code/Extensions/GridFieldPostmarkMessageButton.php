<?php
/**
 * Created by Nivanka Fonseka (nivanka@silverstripers.com).
 * User: nivankafonseka
 * Date: 9/6/15
 * Time: 9:50 AM
 * To change this template use File | Settings | File Templates.
 */

class GridFieldPostmarkMessageButton implements GridField_ColumnProvider, GridField_ActionProvider
{



    public function augmentColumns($gridField, &$columns)
    {
        if (!in_array('Actions', $columns)) {
            $columns[] = 'Actions';
        }
    }

    public function getColumnAttributes($gridField, $record, $columnName)
    {
        return array('class' => 'col-buttons');
    }


    public function getColumnMetadata($gridField, $columnName)
    {
        if ($columnName == 'Actions') {
            return array('title' => '');
        }
    }


    public function getColumnsHandled($gridField)
    {
        return array('Actions');
    }

    /**
     * Which GridField actions are this component handling.
     *
     * @param GridField $gridField
     * @return array
     */

    public function getActions($gridField)
    {
        return array('message', 'postmessage');
    }

    public function getColumnContent($gridField, $record, $columnName)
    {
        Requirements::javascript(POSTMARK_RELATIVE_PATH . '/javascript/Messages.js');
        Requirements::css(POSTMARK_RELATIVE_PATH . '/css/GridFieldPostmarkMessageButton.css');
        $field = GridField_FormAction::create($gridField,  'MessageCustomer'.$record->ID, false, "message",
            array('RecordID' => $record->ID))
            ->addExtraClass('gridfield-button-pencil gird-field-message')
            ->setAttribute('title', 'Message')
            ->setAttribute('data-icon', 'envelope')
            ->setDescription('Send Message');


        return $field->Field();
    }

    public function handleAction(GridField $gridField, $actionName, $arguments, $data)
    {
        $item = $gridField->getList()->byID($arguments['RecordID']);
        if (!$item) {
            return;
        }
        if ($actionName == 'message') {
            $admin = new PostmarkAdmin();
            return $admin->MessageForm(null, $item->ID)->forTemplate();
        } elseif ($actionName == 'postmessage') {
        }
    }
}
