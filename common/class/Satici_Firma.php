<?php

/**
 * Created by PhpStorm.
 * User: Jeppe
 * Date: 25.03.2017
 * Time: 15:00
 */
class Satici_Firma extends Data_Out{

    public function __construct( $id = null ){
        $db_keys = array( "id", "gid", "isim", "vergi_no" );
        parent::__construct( DBT_STOK_FIRMARLAR, $db_keys, $id );
    }

    public function ekle( $input ){

        if( count($this->pdo->query("SELECT * FROM " . $this->table . " WHERE isim = ? || vergi_no = ?", array( $input["isim"], $input["vergi_no"] ) )->results() ) != 0 ){
            $this->return_text = "Bu firma zaten kayıtlı.";
            $this->ok = false;
            return false;
        }

        $this->details["gid"] = Gitas_Hash::hash_olustur(Gitas_Hash::$STOK_FIRMA, array( "vergi_no" => $input["vergi_no"] ) );
        $ekle = $this->pdo->insert($this->table, array(
            "gid"               => $this->details["gid"],
            "isim"              => $input["isim"],
            "vergi_dairesi"     => $input["vergi_dairesi"],
            "vergi_no"          => $input["vergi_no"],
            "telefon_1"         => $input["telefon_1"],
            "telefon_2"         => $input["telefon_2"],
            "eposta"            => $input["eposta"],
            "aciklama"          => $input["aciklama"]
        ));
        if( !$ekle ){
            $this->return_text = "Firma eklenirken bir hata oluştu.";
            $this->ok = false;
            return false;
        }
        $this->details["isim"] = $input["isim"];
        $this->return_text = "Firma eklendi.";
        return true;
    }

}