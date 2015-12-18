<?php
/**
 * Created by Nivanka Fonseka (nivanka@silverstripers.com).
 * User: nivankafonseka
 * Date: 9/24/15
 * Time: 3:48 PM
 * To change this template use File | Settings | File Templates.
 */

class PostmarkHelper extends Object
{

    private static $locales = null;

    public static function client_list()
    {
        return DataList::create(Config::inst()->get('PostmarkAdmin', 'member_class'));
    }

    public static function find_client($id)
    {
        return self::client_list()->byId($id);
    }

    public static function find_client_by_email($email)
    {
        return self::client_list()->filter('Email', $email)->first();
    }

    public static function find_or_make_client($email)
    {
        $client = self::find_client_by_email($email);
        if (!$client) {
            $dataType = Config::inst()->get('PostmarkAdmin', 'member_class');
            $client = new $dataType();
            $client->Email = $email;
            $client->write();
        }
        return $client;
    }

    public static function find_client_emails($ids)
    {
        $list = self::client_list()->filter('ID', $ids);
        if ($list->count()) {
            return $list->column('Email');
        }
    }

    public static function update_attachments($strContent)
    {
        $strContent = preg_replace_callback('/src\s*=\s*"cid[^"]*"/mi', function ($matches) {
            $strURL = $matches[0];
            $strURL = str_replace('src=', '', $strURL);
            $strURL = str_replace('"', '', $strURL);
            $strURL = str_replace('\'', '', $strURL);
            $strURL = str_replace('cid:', '', $strURL);

            return 'src="' . Director::absoluteBaseURL() . 'postmark-attachments/a/' . $strURL . '"';

        }, $strContent);

        return $strContent;
    }

    public static function MergeTags()
    {
        $arrRet = array();
        $className = Config::inst()->get('PostmarkAdmin', 'member_class');
        $object = singleton($className);
        if ($db = $object->db()) {
            foreach ($db as $var => $type) {
                $arrRet[$var] = $var;
            }
        }

        if ($casting = Config::inst()->get($className, 'casting')) {
            foreach ($casting as $var => $type) {
                $arrRet[] = $var;
            }
        }
        return $arrRet;
    }

    public static function MergeEmailText($text, $customer)
    {
        $strRet = $text;
        $arrTags = self::MergeTags();
        foreach ($arrTags as $tag) {
            if (method_exists($customer, $tag)) {
                $strRet = str_replace('{' . $tag . '}', $customer->$tag(), $strRet);
            } else {
                $strRet = str_replace('{' . $tag . '}', $customer->getField($tag), $strRet);
            }
        }
        return $strRet;
    }


    public static function MemberLocales()
    {
        if (self::$locales) {
            return self::$locales;
        }
        self::$locales = array();
        $object = singleton(Config::inst()->get('PostmarkAdmin', 'member_class'));
        if ($db = $object->db()) {
            if (array_key_exists('Locale', $db)) {
                $locales = DB::query('SELECT DISTINCT Locale AS Locale FROM ' . Config::inst()->get('PostmarkAdmin', 'member_class') . ' WHERE Locale IS NOT NULL');
                foreach ($locales as $row) {
                    self::$locales[$row['Locale']] = i18n::get_language_name(substr($row['Locale'], 0, 2));
                }
            }
        }
        return self::$locales;
    }
}
