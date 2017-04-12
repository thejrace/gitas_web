<?php

/**
 * Created by PhpStorm.
 * User: Jeppe
 * Date: 21.03.2017
 * Time: 14:28
 */
class Otobus_Model extends Data_Out {

    public function __construct( $id = null ){
        $db_keys = array( "id", "isim" );
        parent::__construct( DBT_OTOBUS_MODELLER, $db_keys, $id );
    }

    public function ekle( $input ){

        $kontrol = $this->pdo->query("SELECT * FROM " . $this->table ." WHERE isim = ? && marka = ?",array($input["isim"], $input["marka"]))->results();
        if( count($kontrol) != 0 ){
            $this->return_text = "Bu model zaten kayıtlı.";
            $this->ok = false;
            return false;
        }

        $ekle = $this->pdo->insert($this->table, array(
            "marka" => $input["marka"],
            "isim"  => $input["isim"]
        ));
        if( !$ekle ){
            $this->return_text = "Model eklenirken bir hata oluştu.";
            $this->ok = false;
            return false;
        }

        $this->return_text = "Model eklendi.";

    }


}