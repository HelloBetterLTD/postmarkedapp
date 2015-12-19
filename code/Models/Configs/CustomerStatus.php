<?php
/**
 * Created by Nivanka Fonseka (nivanka@silverstripers.com).
 * User: nivankafonseka
 * Date: 9/6/15
 * Time: 9:41 AM
 * To change this template use File | Settings | File Templates.
 */

class CustomerStatus extends DataObject
{

    private static $db = array(
        'Title'                => 'Varchar',
        'Sort'                => 'Int'
    );

    private static $default_sort = 'Sort';
}
