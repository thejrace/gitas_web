<?php
/**
 * Created by PhpStorm.
 * User: Jeppe
 * Date: 06.03.2017
 * Time: 11:48
 */

abstract class Data_Out {

    protected   $pdo,
                $details = array(),
                $exists = false,
                $ok = true,
                $return_text,
                $table;

    public function __construct( $db_table, $db_keys, $id = null ){
        $this->pdo = DB::getInstance();
        $this->table = $db_table;
        if( isset($id) ) {
            // tanimlamada gelen degeri db deki unique keylerden tariyoruz herhangi biri uyarsa diye
            foreach ($db_keys as $key) {
                $query = $this->pdo->query("SELECT * FROM " . $db_table . " WHERE " . $key . " = ? ", array($id))->results();
                if (count($query) > 0) {
                    $this->exists = true;
                    $this->details = $query[0];
                    break;
                }
            }
        }
    }

    public function get_details( $key = null ){
        if( isset($key) ) return $this->details[$key];
        return $this->details;
    }

    public function get_return_text(){
        return $this->return_text;
    }

    public function exists(){
        return $this->exists;
    }

    public function is_ok(){
        return $this->ok;
    }


}