<?php

class Lookup
{
    static $lookup_data = [];

    public static function init()
    {

        $lookup_tables = array('lu_gender', 'lu_occasion', 'lu_budget', 'lu_body_type', 'lu_entity_type');
        foreach ($lookup_tables as $table) {
            $sql = "SELECT id, lower(name) FROM $table";
            $result = mysql_query($sql);

            while ($data = mysql_fetch_array($result)) {
                self::$lookup_data[$table][$data[1]] = $data[0];
            }
        }
    }

    public static function getId($type, $name)
    {
        if (self::$lookup_data == []) {
            self::init();
        }

        $lookup_table = "lu_" . $type;

        if ($type != "" && isset(self::$lookup_data[$lookup_table])) {

            $lookup_keyname = strtolower($name);

            if (isset(self::$lookup_data[$lookup_table][$lookup_keyname])) {
                return self::$lookup_data[$lookup_table][$lookup_keyname];
            }
        }
        return '';
    }
}