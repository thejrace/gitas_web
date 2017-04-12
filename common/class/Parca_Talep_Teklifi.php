<?php

/**
 * Created by PhpStorm.
 * User: Jeppe
 * Date: 07.03.2017
 * Time: 21:34
 */
class Parca_Talep_Teklifi extends Data_Out{

    public function __construct( $id = null ){
        $db_keys = array( "id", "gid" );
        parent::__construct( DBT_PARCA_TALEP_TEKLIFLERI, $db_keys, $id );
    }

    public function ekle( $input ){

        $ekle = $this->pdo->insert( $this->table, array(
            "talep_gid"             => $input["talep_gid"],
            "firma"                 => $input["firma"],
            "aciklama"              => $input["aciklama"],
            "duzenleyen_personel"   => Active_User::get_details("id"),
            "durum"                 => Durum_Kodlari::$AKTIF,
            "tarih"                 => Common::get_current_datetime()
        ));
        if( !$ekle ){
            $this->return_text = "Teklif eklenirken bir hata oluştu.";
            return false;
        }
        $this->return_text = "Teklif eklendi.";
        return true;

    }

    public function durum_guncelle( $input ){

    }

}