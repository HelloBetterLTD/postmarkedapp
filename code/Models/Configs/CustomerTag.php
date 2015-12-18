<?php
/**
 * Created by Nivanka Fonseka (nivanka@silverstripers.com).
 * User: nivankafonseka
 * Date: 9/6/15
 * Time: 9:42 AM
 * To change this template use File | Settings | File Templates.
 */

class CustomerTag extends DataObject
{

    private static $db = array(
        'Title'                => 'Varchar'
    );

    private static $default_sort = 'Title';
}
