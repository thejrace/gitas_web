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
        $statdata = array(
            array(
                "header" => "PARÇA DETAYLARI",
                "items"  => array(
                    array( "key" => "PARÇA TİPİ", "val" => $Parca_Tipi->get_details("isim")  ),
                    array( "key" => "STOK KODU", "val" => $this->details["stok_kodu"] ),
                    array( "key" => "AÇIKLAMA", "val" => $this->details["aciklama"] ),
                    array( "key" => "STOĞA GİRİŞ TARİHİ", "val" => $pgiris ),
                    array( "key" => "FATURA NO", "val" => $this->details["fatura_no"] ),
                    array( "key" => "ALINAN FİRMA", "val" => $sfirma ),
                    array( "key" => "GARANTİ", "val" => $this->details["garanti_suresi"] ),
                )
            )
        );
        return Popup_Stats::init( $statdata );
    }

    public function revizyon_taleplerini_listele(){
        $output = array();
        foreach( $this->pdo->query("SELECT * FROM " . DBT_REVIZYON_TALEPLERI . " WHERE stok_kodu = ?", array( $this->details["stok_kodu"]))->results() as $talep ){
            $output[] = $talep["gid"];
        }
        return $output;
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