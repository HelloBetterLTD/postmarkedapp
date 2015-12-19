<?php
/**
 * Created by Nivanka Fonseka (nivanka@silverstripers.com).
 * User: nivankafonseka
 * Date: 9/25/15
 * Time: 5:40 PM
 * To change this template use File | Settings | File Templates.
 */

class CustomerExtension extends DataExtension
{

    private static $many_many = array(
        'Tags'            => 'CustomerTag',
        'Statuses'        => 'CustomerStatus'
    );

    private static $casting = array(
        'getFullName'            => 'Varchar',
        'getTagCollection'        => 'Varchar',
        'getStatusCollection'    => 'Varchar',
        'getNotifications'        => 'Int'
    );

    public function getFullName()
    {
        return $this->owner->FirstName . ' ' . $this->owner->Surname;
    }

    public function getTagCollection()
    {
        $tags = $this->owner->Tags();
        if ($tags->count()) {
            $arrTags = $tags->column('Title');
            return implode(',', $arrTags);
        }
    }

    public function getStatusCollection()
    {
        $statuses = $this->owner->Statuses();
        if ($statuses->count()) {
            $arrTags = $statuses->column('Title');
            return implode(',', $arrTags);
        }
    }

    public function getUnreadMessages()
    {
        $list = PostmarkMessage::get()->filter(array(
            'FromCustomerID'        => $this->owner->ID,
            'Read'                    => 0
        ));
        return $list->count();
    }
}
