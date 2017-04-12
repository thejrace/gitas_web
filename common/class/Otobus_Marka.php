<?php

/**
 * Created by PhpStorm.
 * User: Jeppe
 * Date: 21.03.2017
 * Time: 14:28
 */
class Otobus_Marka extends Data_Out {

    public function __construct( $id = null ){
        $db_keys = array( "id", "isim" );
        parent::__construct( DBT_OTOBUS_MARKALAR, $db_keys, $id );
    }

    public function ekle($input){

        $kontrol = $this->pdo->query("SELECT * FROM " . $this->table ." WHERE isim = ?",array($input["isim"]))->results();
        if( count($kontrol) != 0 ){
            $this->return_text = "Bu marka zaten kayıtlı.";
            $this->ok = false;
            return false;
        }

        $ekle = $this->pdo->insert($this->table, array(
            "isim"  => $input["isim"]
        ));
        if( !$ekle ){
            $this->return_text = "Marka eklenirken bir hata oluştu.";
            $this->ok = false;
            return false;
        }

        $this->return_text = "Marka eklendi.";

    }


}