<?php

/**
 * Created by PhpStorm.
 * User: Jeppe
 * Date: 07.03.2017
 * Time: 21:32
 */
class Parca_Talebi extends Data_Out{


    public function __construct( $id = null ){
        $db_keys = array( "id", "gid" );
        parent::__construct( DBT_PARCA_TALEPLERI, $db_keys, $id );
    }

    public function ekle( $input ){

        $this->details["gid"] = $this->details["gid"] = Gitas_Hash::hash_olustur(Gitas_Hash::$PARCA_TALEP, array( "parca_tipi" => $input["parca_tipi"] ) );

        $ekle = $this->pdo->insert( $this->table, array(
            "gid"                   => $this->details["gid"],
            "form_gid"              => $input["form_gid"], // formsuz girişte 0 olacak
            "parca_tipi"            => $input["parca_tipi"],
            "adet"                  => $input["adet"],
            "aciklama"              => $input["aciklama"],
            "duzenleyen_personel"   => Active_User::get_details("id"),
            "tarih"                 => Common::get_current_datetime(),
            "durum"                 => Durum_Kodlari::$AKTIF,
        ));
        if( !$ekle ){
            $this->return_text = "Talep eklenirken bir hata oluştu.";
            return false;
        }
        $this->return_text = "Parça talebi eklendi.";
        return true;

    }

    public function teklif_ekle( $input ){
        $Teklif = new Parca_Talep_Teklifi();
        if( !$Teklif->ekle($input) ){
            $this->return_text = $Teklif->get_return_text();
            return false;
        }
        $this->return_text = $this->return_text = $Teklif->get_return_text();
        return true;
    }


}