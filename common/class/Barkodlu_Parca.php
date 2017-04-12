<?php

/**
 * Created by PhpStorm.
 * User: Jeppe
 * Date: 06.03.2017
 * Time: 13:38
 */
class Barkodlu_Parca extends Data_Out {
    public function __construct( $id = null ){
        $db_keys = array( "id", "stok_kodu" );
        parent::__construct( DBT_BARKODLU_PARCALAR, $db_keys, $id );
    }

    // parça girişi yaparken db ye eklemeden once gecici olarak veriyi objeden tutuyoruz
    public function set_gecici_data( $data ){
        $this->details = $data;
    }

    public function ekle( $input ){

        $Parca_Tipi = new Parca_Tipi( $input["tip"] );
        if( !$Parca_Tipi->exists() ){
            $this->return_text = "Parça tipi bulunamadı.";
            $this->ok = false;
            return false;
        }

        $gid = Gitas_Hash::hash_olustur( Gitas_Hash::$BARKODLU_PARCA, array( "parca_tipi" => $input["tip"] ) );
        $insert = $this->pdo->insert( $this->table, array(
            "stok_kodu"         => $gid,
            "aciklama"          => $input["aciklama"],
            "tip"               => $input["tip"],
            "fatura_no"         => $input["fatura_no"],
            "satici_firma"      => $input["satici_firma"],
            "garanti_suresi"    => $input["garanti_suresi"],
            "revize"            => 0,
            "hurda"             => 0,
            "kayip"             => 0,
            "kullanildi"        => 0,
            "parca_giris_id"    => $input["parca_giris_id"],
            "durum"             => $input["durum"]
        ));

        if( $insert ){
            $this->details["stok_kodu"] = $gid;
            $this->details["tip"] = $input["tip"];
            $this->return_text = "Parça eklendi.";
        } else {
            $this->return_text = " Parça eklenirken bir hata oluştu. " .  $this->pdo->get_return_text();
            $this->ok = false;
        }
    }

    public function duzenle( $input ){
        $this->pdo->query("UPDATE " . $this->table . " SET aciklama = ? WHERE stok_kodu = ?", array( $input["aciklama"], $this->details["stok_kodu"]));
        $this->return_text = "Parça düzenlendi.";
    }

    // is emri formu duzenlemesini geri almada kullaniyorum
    // hurda, revize, kullanildi sutunlarini guncelliyoruz
    public function form_verisi_guncelleme( $input ){
        $this->pdo->query("UPDATE " . $this->table . " SET hurda = ?, revize = ?, kullanildi = ? WHERE stok_kodu = ?", array(
            $input["hurda"],
            $input["revize"],
            $input["kullanildi"],
            $this->details["stok_kodu"]
        ));
    }

    public function detay_html(){
        $Parca_Tipi = new Parca_Tipi( $this->details["tip"] );
        if( $this->details["parca_giris_id"] == "0" ){
            $pgiris = "Araçtan çıkıp stoğa eklenmiş.";
        } else {
            $Parca_Giris = new Parca_Girisi($this->details["parca_giris_id"] );
            $pgiris = $Parca_Giris->get_details("tarih");
        }
        if( $this->details["satici_firma"] == "0" ){
            $sfirma = "Bilgi yok.";
        } else{
            $Firma = new Satici_Firma( $this->details["satici_firma"] );
            $sfirma = $Firma->get_details("isim");
        }

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
            ),
            array(
                array(
                    "label" => "Stoğa Giriş Tarihi",
                    "value" => $pgiris
                )
            ),
            array(
                array(
                    "label" => "Fatura No / Alınan Firma",
                    "value" => $this->details["fatura_no"] . " / " . $sfirma
                )
            ),
            array(
                array(
                    "label" => "Garanti",
                    "value" => $this->details["garanti_suresi"]
                )
            )
        );
        return Popup_Info::init( $detay_array );
    }

    public function sil(){
        $this->pdo->query("DELETE FROM " . $this->table ." WHERE stok_kodu = ?", array( $this->details["stok_kodu"] ) );
    }

    public function hurda_yap(){
        $this->pdo->query("UPDATE " . $this->table . " SET hurda = 1 WHERE stok_kodu = ?", array($this->details["stok_kodu"]));
        $this->return_text = "Parça hurda durumuna getirildi.";
    }

    public function kayip_yap(){
        $this->pdo->query("UPDATE " . $this->table . " SET kayip = 1 WHERE stok_kodu = ?", array($this->details["stok_kodu"]));
        $this->return_text = "Parça kayip durumuna getirildi.";
    }

    public function revize_yap(){
        $this->pdo->query("UPDATE " . $this->table . " SET revize = 1 WHERE stok_kodu = ?", array($this->details["stok_kodu"]));
        $this->return_text = "Parça revize durumuna getirildi.";
    }

    public function kullanildi_yap(){
        $this->pdo->query("UPDATE " . $this->table . " SET kullanildi = 1 WHERE stok_kodu = ?", array($this->details["stok_kodu"]));
        $this->return_text = "Parça kullanıldı durumuna getirildi.";
    }

    public function garanti_guncelle( $yeni_tarih ){
        $this->pdo->query("UPDATE " . $this->table . " SET garanti_suresi = ? WHERE stok_kodu = ?", array( $yeni_tarih, $this->details["stok_kodu"] ) );
        $this->return_text = "Parçanın garanti süresi güncellendi.";
    }


}