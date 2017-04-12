<?php

/**
 * Created by PhpStorm.
 * User: Jeppe
 * Date: 06.03.2017
 * Time: 19:08
 */
class Personel extends Data_Out {

    public static   $ADMIN      = 0,
                    $SERVIS     = 1,
                    $MUHASEBE   = 2,
                    $SURUCU     = 3;

    public function __construct( $id = null ){
        $db_keys = array( "id", "gid", "isim", "sicil_no", "eposta" );
        parent::__construct( DBT_PERSONEL, $db_keys, $id );
    }

    public function ekle( $input ){

        $Personel_Kontrol = new Personel( $input["eposta"] );
        if( $Personel_Kontrol->exists() ){
            $this->return_text = "Bu eposta adresi zaten kullanımda.";
            $this->ok = false;
            return;
        }

        $salt = utf8_encode( mcrypt_create_iv( 64, MCRYPT_DEV_URANDOM ) );
        // PHP 5.1.2 ve sonrasinda var hash() fonksiyonu
        // sifre ve salti seviştir
        $hash = hash( 'sha256', $salt . $input["pass"] );

        // todo herkes kullanici olsun boylece girip kendi istatistiklerini gorebilsin

        if( $this->pdo->insert($this->table, array(
            "gid"           => Gitas_Hash::hash_olustur( Gitas_Hash::$PERSONEL, array( "seviye" => $input["seviye"], "isim" => $input["isim"] )),
            "seviye"        => $input["seviye"],
            "sicil_no"      => $input["sicil_no"],
            "isim"          => $input["isim"],
            "eposta"        => $input["eposta"],
            "pass"          => $hash,
            "salt"          => $salt,
            "telefon_1"     => $input["telefon_1"],
            "telefon_2"     => $input["telefon_2"]
        )) ){
            $this->return_text = "Personel eklendi.";
        } else {
            $this->return_text = "Personel eklenirken bir hata oluştu.";
            $this->ok = false;
        }

    }

    public function son_giris_guncelle(){
        $this->pdo->query("UPDATE " . $this->table . " SET son_giris = ? WHERE gid = ?", array( Common::get_current_datetime(), $this->details["gid"]));
    }

    public function eposta_duzenle( $input ){
        $Personel_Kontrol = new Personel( $input );
        if( $Personel_Kontrol->exists() && $Personel_Kontrol->get_details("gid") != $this->details["gid"]){
            $this->return_text = "Bu eposta adresi zaten kullanımda.";
            $this->ok = false;
            return;
        }
        $this->duzenle( "eposta", $input );
    }

    public function sifre_degistir( $input ){

    }

    public function duzenle( $key, $val ){
        $this->pdo->query("UPDATE " . $this->table . " SET " . $key . " = ?", array($val));
    }

}