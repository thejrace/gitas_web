<?php

/**
 * Created by PhpStorm.
 * User: Jeppe
 * Date: 07.03.2017
 * Time: 21:35
 */
class Revizyon_Talep_Teklifi extends Data_Out {

    public function __construct( $id = null ){
        $db_keys = array( "id", "gid" );
        parent::__construct( DBT_REVIZYON_TALEP_TEKLIFLERI, $db_keys, $id );
    }

    public function ekle( $input ){
        $ekle = $this->pdo->insert( $this->table, array(
            "talep_gid"             => $input["talep_gid"],
            "firma"                 => $input["satici_firma"],
            "aciklama"              => $input["aciklama"],
            "duzenleyen_personel"   => Active_User::get_details("id"),
            "durum"                 => Revizyon_Talebi::$AKTIF,
            "tarih"                 => Common::get_current_datetime()
        ));
        if( !$ekle ){
            $this->return_text = "Teklif eklenirken bir hata oluştu.";
            return false;
        }
        $Talep = new Revizyon_Talebi( $input["talep_gid"] );
        $Talep->durum_guncelle( Revizyon_Talebi::$TEKLIF_ONAYI_BEKLENIYOR );
        $this->return_text = "Teklif eklendi.";
        return true;

    }

    public function onayla(){
        $this->pdo->query("UPDATE " . $this->table . " SET durum = ? WHERE id = ?", array( Revizyon_Talebi::$TAMAMLANDI, $this->details["id"]));
    }


}