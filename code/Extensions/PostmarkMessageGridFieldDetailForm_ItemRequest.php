<?php
/**
 * Created by Nivanka Fonseka (nivanka@silverstripers.com).
 * User: nivankafonseka
 * Date: 9/25/15
 * Time: 3:52 PM
 * To change this template use File | Settings | File Templates.
 */

class PostmarkMessageGridFieldDetailForm_ItemRequest extends GridFieldDetailForm_ItemRequest
{

    private static $allowed_actions = array(
        'edit',
        'view',
        'read',
        'ItemEditForm'
    );


    public function ItemEditForm()
    {
        Requirements::css('silverstripe-postmarked/css/messages.css');
        Requirements::javascript('silverstripe-postmarked/javascript/Messages.js');
        $form = parent::ItemEditForm();
        $form->setTemplate('PostmarkEditorTemplate');


        if ($this->record) {
            $message = PostmarkMessage::get()->byID($this->record->ID);
            if (!$message->Read) {
                $message->Read = 1;
                $message->write();
            }
        }

        return $form;
    }

    public function getRecord()
    {
        return $this->record;
    }

    public function read($request)
    {
        if (!$this->record->canView()) {
            $this->httpError(403);
        }
        if (isset($_GET['m'])) {
            $message = PostmarkMessage::get()->byID($_GET['m']);
            if (!$message->Read) {
                $message->Read = 1;
                $message->write();
            }
        }
    }


    public function ReadLink()
    {
        return $this->Link('read');
    }
}
