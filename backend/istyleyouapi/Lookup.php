<?php
class Lookup{
    static $lookup_data = [];

    public static function init(){

        $lookup_tables = array('lu_gender', 'lu_occasion', 'lu_budget', 'lu_body_type');
        foreach($lookup_tables as $table){
            $sql = "SELECT id, lower(name) FROM $table";
            $result = mysql_query($sql);

            while ($data = mysql_fetch_array($result)) {
                self::$lookup_data[$table][$data[1]] = $data[0];
            }
        }
    }

    public static function getId($type, $name){
        if(self::$lookup_data == []){
            self::init();
        }

        if($type == "" || !isset(self::$lookup_data["lu_" . $type])){
            return null;
        }

        return self::$lookup_data["lu_" . $type][strtolower($name)];
    }
}