<?php
/**
 * Created by Nivanka Fonseka (nivanka@silverstripers.com).
 * User: nivankafonseka
 * Date: 9/25/15
 * Time: 6:48 PM
 * To change this template use File | Settings | File Templates.
 */

class QuillEditorField extends TextareaField
{

    public function Field($properties = array())
    {
        $this->addExtraClass('stacked');
        Requirements::javascript(POSTMARK_RELATIVE_PATH . '/thirdparty/quill/quill.min.js');
        Requirements::javascript(POSTMARK_RELATIVE_PATH . '/javascript/QuillEditorField.js');
        Requirements::css(POSTMARK_RELATIVE_PATH . '/thirdparty/quill/quill.snow.css');
        Requirements::css(POSTMARK_RELATIVE_PATH . '/css/QuillEditorField.css');

        return parent::Field($properties);
    }


    public function FieldHolder($properties = array())
    {
        $this->addExtraClass('stacked');
        return parent::FieldHolder($properties);
    }
}
