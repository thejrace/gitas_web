<?php

/**
 * Created by PhpStorm.
 * User: Jeppe
 * Date: 05.05.2017
 * Time: 12:49
 */
class Varyant extends Data_Out{

    public function __construct( $id = null ){
        $db_keys = array( "id", "gid", "isim" );
        parent::__construct( DBT_VARYANTLAR, $db_keys, $id );
    }

    public function ekle( $input ){
        $this->details["gid"] = Gitas_Hash::hash_olustur( Gitas_Hash::$VARYANT, array("isim" => $input["isim"] ) );
        $insert = array(
            "gid" => $this->details["gid"],
            "isim" => $input["isim"]
        );
        if( isset($input["parent"] ) ) $insert["parent"] = $input["parent"];
        if( $this->pdo->insert( $this->table, $insert )){
            $this->return_text = "Varyant eklendi.";
        } else {
            $this->ok = false;
            $this->return_text = "Varyant eklenirken bir hata oluştu.";
        }
    }

    /** Parça girişi yaparken listenelecek varyantlar => parent = NULL
        Eğer parça tipine sub varyant tanımı yoksa, parent varyantlar hem giriş - hem çıkışta listenelecek
     */
    public function parca_tipine_tanimla( $parca_tipi ){
        $insert = $this->pdo->insert(DBT_VARYANT_TANIMLAMALAR, array(
            "varyant_gid" => $this->details["gid"],
            "parca_tipi" => $parca_tipi
        ));
        if( $insert ){
            $this->return_text = "Varyant parça tipine tanımlandı.";
        } else {
            $this->return_text = "Varyant parça tipine tanımlanırken bir hata oluştu.";
            $this->ok = false;
        }
    }

    public function tanimlamayi_kaldir( $parca_tipi ){
        $this->pdo->query("DELETE FROM " . DBT_VARYANT_TANIMLAMALAR . " WHERE varyant_gid = ? && parca_tipi = ?", array( $this->details["gid"], $parca_tipi ) );
    }

    public function alt_varyantlari_listele(){
        $output = array();
        foreach( $this->pdo->query("SELECT * FROM " . $this->table . " WHERE parent = ?", array($this->details["gid"] ) )->results() as $alt_varyant ){
            $Varyant = new Varyant( $alt_varyant["gid"] );
            $output[] = array( "gid" => $alt_varyant["gid"], "isim" => $Varyant->get_details("isim") );
        }
        return $output;
    }


    public function parca_tipine_tanimli_alt_varyantlari_listele( $parca_tipi ){

    }
}