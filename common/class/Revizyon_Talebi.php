<?php

/**
 * Created by PhpStorm.
 * User: Jeppe
 * Date: 07.03.2017
 * Time: 21:32
 */
class Revizyon_Talebi extends Data_Out {

    public function __construct( $id = null ){
        $db_keys = array( "id", "gid" );
        parent::__construct( DBT_REVIZYON_TALEPLERI, $db_keys, $id );
    }

    public function ekle( $input ){

        $this->details["gid"] = Gitas_Hash::hash_olustur(Gitas_Hash::$REVIZYON_TALEP, array( "form_id" => $input["form_gid"] ) );
        $insert = $this->pdo->insert( $this->table, array(
            "gid"                   => $this->details["gid"],
            "form_gid"              => $input["form_gid"],
            "stok_kodu"             => $input["stok_kodu"],
            "aciklama"              => $input["aciklama"],
            "duzenleyen_personel"   => Active_User::get_details("id"),
            "durum"                 => Durum_Kodlari::$AKTIF,
            "tarih"                 => Common::get_current_datetime()
        ));

        if( !$insert ){
            $this->return_text = "Talep eklenirken bir hata oluştu.";
            return false;
        }
        $this->return_text = "Talep eklendi.";
        return true;
    }

    public function teklif_ekle( $input ){
        $Teklif = new Revizyon_Talep_Teklifi();
        if( !$Teklif->ekle($input) ){
            $this->return_text = $Teklif->get_return_text();
            return false;
        }
        $this->return_text = $this->return_text = $Teklif->get_return_text();
        return true;
    }

}