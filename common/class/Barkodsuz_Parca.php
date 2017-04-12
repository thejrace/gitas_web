<?php

/**
 * Created by PhpStorm.
 * User: Jeppe
 * Date: 06.03.2017
 * Time: 14:22
 */
class Barkodsuz_Parca extends Data_Out {

    public static   $GIRIS = 1, // sadece giriş
                    $CIKIS = 0, // sadece çıkış
                    $GC = 2;    // hem giriş hem çıkış

    public function __construct( $id = null ){
        $db_keys = array( "id", "aciklama" ,"stok_kodu" );
        parent::__construct( DBT_BARKODSUZ_PARCALAR, $db_keys, $id );
    }

    // parça girişi yaparken db ye eklemeden once gecici olarak veriyi objeden tutuyoruz
    public function set_gecici_data( $data ){
        $this->details = $data;
    }


    // details in uzerine yazma
    public function add_gecici_data( $data ){
        foreach( $data as $key => $val ){
            $this->details[$key] = $val;
        }
    }

    public function ekle( $input ){
        $Parca_Tipi = new Parca_Tipi( $input["tip"] );
        if( !$Parca_Tipi->exists() ){
            $this->return_text = "Parça tipi bulunamadı.";
            $this->ok = false;
            return false;
        }

        $Barkodlu_Parca_Kontrol = new Barkodsuz_Parca( $input["aciklama"] );
        if( $Barkodlu_Parca_Kontrol->exists() ){
            $this->return_text = "Bu parça zaten eklenmiş.";
            $this->ok = false;
            return false;
        }

        if( $this->pdo->insert( $this->table, array(
            "aciklama"          => $input["aciklama"],
            "stok_kodu"         => Gitas_Hash::hash_olustur( Gitas_Hash::$BARKODSUZ_PARCA, array( "parca_tipi" => $input["tip"], "aciklama" => $input["aciklama"] ) ),
            "tip"               => $input["tip"],
            "miktar"            => $input["miktar"],
            "gcmod"             => $input["gcmod"]
        )) ){
            $this->return_text = "Parça eklendi.";
        } else {
            $this->ok = false;
            $this->return_text = "Parça eklenirken bir hata oluştu.";
        }

    }

    public function detay_html(){
        $Parca_Tipi = new Parca_Tipi( $this->details["tip"] );

        $detay_array = array(
            array(
                array(
                    "label" => "Parça Tipi",
                    "value" => $Parca_Tipi->get_details("isim")
                )
            ),
            array(
                array(
                    "label" => "Stok Kodu",
                    "value" => $this->details["stok_kodu"]
                )
            ),
            array(
                array(
                    "label" => "Açıklama",
                    "value" => $this->details["aciklama"]
                )
            )
        );
        return Popup_Info::init( $detay_array );
    }

    private function miktar_duzenle( $input ){
        return $this->pdo->query("UPDATE " . $this->table . " SET miktar = ? WHERE id = ?", array( $input, $this->details["id"] ) );
    }

    public function stok_ekle( $eklenen ){
        $this->miktar_duzenle( $this->details["miktar"] + $eklenen );
        $this->return_text = "Stok güncellendi.";
    }

    public function kullan( $adet ){
        $yeni_miktar = $this->details["miktar"] - $adet;
        /*if( $yeni_miktar == 0 || $yeni_miktar < 0 ){
            $this->ok = false;
            $this->return_text = "Stok yetersiz.";
        } else {*/
            $this->miktar_duzenle( $yeni_miktar );
            $this->return_text = "Kullanılan miktar stoktan düşürüldü.";
        //}
    }

    public function sil(){

    }



}