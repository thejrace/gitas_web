<?php

/**
 * Created by PhpStorm.
 * User: Jeppe
 * Date: 05.03.2017
 * Time: 22:25
 */
class DBSetup{


    public function tablolari_olustur(){

        $tablolar = array(
            'parca_tipleri' => ' (
                id                                  int         AUTO_INCREMENT,
                gid                                 text        NOT NULL,
                isim                                text        NOT NULL,
                tip                                 int         NOT NULL,
                kategori                            int         NOT NULL,
                miktar_olcu_birimi                  text        NOT NULL,
                ideal_degisim_sikligi_alt           double DEFAULT 0,
                ideal_degisim_sikligi_ust           double DEFAULT 0,
                ideal_degisim_sikligi_tarih_alt     int DEFAULT 0,
                ideal_degisim_sikligi_tarih_ust     int DEFAULT 0,
                kritik_seviye_limiti                double DEFAULT 0,
                PRIMARY KEY( id )
            ) ',
            'barkodlu_parcalar' => ' (
                id                      int         AUTO_INCREMENT,
                stok_kodu               text        NOT NULL,
                aciklama                text,
                tip                     text        NOT NULL,
                fatura_no               int,
                satici_firma            text,
                garanti_suresi          date,
                revize                  int         DEFAULT 0,
                hurda                   int         DEFAULT 0,
                kullanildi              int         DEFAULT 0,
                kayip                   int         DEFAULT 0,
                parca_giris_id          text        NOT NULL,
                durum                   int         DEFAULT 0,
                PRIMARY KEY( id )
            )',
            'barkodsuz_parcalar' => ' (
                id                      int         AUTO_INCREMENT,
                aciklama                text,
                stok_kodu               text        NOT NULL,
                tip                     text        NOT NULL,
                miktar                  int         DEFAULT 0,
                gcmod                   int,
                PRIMARY KEY( id )
            ) ',
            'parca_girisleri' => ' (
                id                      int         AUTO_INCREMENT,
                gid                     text        NOT NULL,
                giris_yapan             text        NOT NULL,
                tarih                   datetime,
                PRIMARY KEY( id )
            ) ',
            'barkodsuz_parca_girisleri_icerik' => ' (
                id                      int         AUTO_INCREMENT,
                parca_giris_gid         text        NOT NULL,
                parca_stok_kodu         text        NOT NULL,
                miktar                  int         DEFAULT 0,
                fatura_no               int         NOT NULL,
                satici_firma            text        NOT NULL,
                PRIMARY KEY( id )
            ) ',
            'is_emri_formlari' => ' (
                id                      int         AUTO_INCREMENT,
                gid                     text        NOT NULL,
                plaka                   text        NOT NULL,
                aktif_kapi_no           text,
                gelis_km                double      NOT NULL,
                surucu                  text        NOT NULL,
                gelis_tarih             datetime    NOT NULL,
                cikis_tarih             datetime    NOT NULL,
                sikayet                 text        NOT NULL,
                ariza_tespit            text        NOT NULL,
                yapilan_onarim          text        NOT NULL,
                araca_yikama            int         DEFAULT \'0\',
                kalibrasyon_yapildi     int         DEFAULT \'0\',
                durum                   int         NOT NULL,
                giris_yapan             text        NOT NULL,
                tarih                   datetime    NOT NULL,
                PRIMARY KEY( id )
            ) ',
            'is_emri_formu_cikanlar' => ' (
                id                      int         AUTO_INCREMENT,
                form_gid                text        NOT NULL,
                tip                     int         NOT NULL,
                stok_kodu               text        NOT NULL,
                durum                   int         NOT NULL,
                miktar                  double,
                PRIMARY KEY( id )
            ) ',
            'is_emri_formu_girenler' => ' (
                id                      int         AUTO_INCREMENT,
                form_gid                text        NOT NULL,
                tip                     int         NOT NULL,
                stok_kodu               text        NOT NULL,
                ekleme                  int         DEFAULT \'0\'
                miktar                  double,
                PRIMARY KEY( id )
            ) ',
            'is_emri_formu_personel_detay' => ' (
                id                      int         AUTO_INCREMENT,
                form_gid                text        NOT NULL,
                personel                text        NOT NULL,
                is_tanimi               text        NOT NULL,
                baslama                 datetime    NOT NULL,
                bitis                   datetime    NOT NULL,
                PRIMARY KEY( id )
            ) ',
            'ariza_bildirimleri' => ' (
                id                      int         AUTO_INCREMENT,
                gid                     text        NOT NULL,
                personel                text        NOT NULL,
                form_gid                text,
                sikayet                 text        NOT NULL,
                tarih                   datetime    NOT NULL,
                PRIMARY KEY( id )
            ) ',
            'ariza_bildirimleri_resimler' => ' (
                id                      int         AUTO_INCREMENT,
                bildirim_gid            text        NOT NULL,
                url                     text        NOT NULL,
                PRIMARY KEY( id )
            ) ',
            'parca_talepleri' => ' (
                id                      int         AUTO_INCREMENT,
                gid                     text        NOT NULL,
                form_gid                text,
                parca_tipi              text        NOT NULL,
                adet                    int         NOT NULL,
                aciklama                text        NOT NULL,
                duzenleyen_personel     text        NOT NULL,
                ilgili_personel         text,
                tarih                   datetime    NOT NULL,
                durum                   int         NOT NULL,
                PRIMARY KEY( id )
            ) ',
            'parca_talep_teklifleri' => ' (
                id                      int         AUTO_INCREMENT,
                talep_gid               text        NOT NULL,
                firma                   text        NOT NULL,
                aciklama                text        NOT NULL,
                duzenleyen_personel     text        NOT NULL,
                ilgili_personel         text,
                durum                   int         NOT NULL,
                tarih                   datetime    NOT NULL,
                PRIMARY KEY( id )
            ) ',
            'stok_firmalar' => ' (
                id                      int         AUTO_INCREMENT,
                gid                     text        NOT NULL,
                isim                    text        NOT NULL,
                vergi_dairesi           text        NOT NULL,
                vergi_no                text        NOT NULL,
                telefon_1               text,
                telefon_2               text,
                eposta                  text,
                aciklama                text,
                PRIMARY KEY( id )
            ) ',
            'revizyon_talepleri' => ' (
                id                      int         AUTO_INCREMENT,
                gid                     text        NOT NULL,
                form_gid                text        NOT NULL,
                stok_kodu               text        NOT NULL,
                aciklama                text,
                duzenleyen_personel     text        NOT NULL,
                ilgili_personel         text,
                durum                   int         NOT NULL,
                tarih                   datetime    NOT NULL,
                PRIMARY KEY( id )
            ) ',
            'revizyon_talep_teklifleri' => ' (
                id                      int         AUTO_INCREMENT,
                talep_gid               text        NOT NULL,
                firma                   text        NOT NULL,
                aciklama                text        NOT NULL,
                duzenleyen_personel     text        NOT NULL,
                ilgili_personel         text,
                durum                   int         NOT NULL,
                tarih                   datetime    NOT NULL,
                PRIMARY KEY( id )
            ) ',
            'otobusler' => ' (
                id                      int         AUTO_INCREMENT,
                plaka                   text        NOT NULL,
                ruhsat_kapi_kodu        text        NOT NULL,
                aktif_kapi_kodu         text,
                marka                   text        NOT NULL,
                model                   text        NOT NULL,
                model_yili              int         NOT NULL,
                sahip                   text        NOT NULL,
                ogs                     text,
                durum                   int,
                PRIMARY KEY( id )
            ) ',
            'otobus_markalar' => ' (
                id                      int         AUTO_INCREMENT,
                isim                    text        NOT NULL,
                PRIMARY KEY( id )
            ) ',
            'otobus_modeller' => ' (
                id                      int         AUTO_INCREMENT,
                marka                   text        NOT NULL,
                isim                    text        NOT NULL,
                PRIMARY KEY( id )
            ) ',
            'personel' => ' (
                id                      int         AUTO_INCREMENT,
                gid                     text        NOT NULL,
                seviye                  int         NOT NULL,
                sicil_no                text,
                isim                    text        NOT NULL,
                eposta                  text,
                pass                    text        NOT NULL,
                salt                    text        NOT NULL,
                telefon_1               text        NOT NULL,
                telefon_2               text        NOT NULL,
                son_giris               datetime,
                PRIMARY KEY( id )
            ) ',
            'mesajlar' => ' (
                id                      int         AUTO_INCREMENT,
                gid                     text        NOT NULL,
                gonderen                text        NOT NULL,
                mesaj                   text        NOT NULL,
                goruldu                 int         NOT NULL,
                PRIMARY KEY( id )
            ) ',
            'mesajlar_alicilar' => ' (
                id                      int         AUTO_INCREMENT,
                mesaj_gid               text        NOT NULL,
                alici                   text        NOT NULL,
                PRIMARY KEY( id )
            ) ',
            'takvim_kayitlari' => ' (
                id                      int         AUTO_INCREMENT,
                gid                     text        NOT NULL,
                kapsama                 int         NOT NULL,
                is_tanimi               text        NOT NULL,
                icerik                  text,
                goruldu                 int         NOT NULL,
                tarih                   datetime    NOT NULL,
                PRIMARY KEY( id )
            ) ',
            'basarisiz_girisler' => ' (
                id                      int         AUTO_INCREMENT,
                eposta                  text        NOT NULL,
                ip                      text        NOT NULL,
                mesaj                   text        NOT NULL,
                tarih                   datetime    NOT NULL,
                PRIMARY KEY( id )
            ) ',
            'aktivite_kayit' => ' (
                id                      int         AUTO_INCREMENT,
                personel                text        NOT NULL,
                aktivite                text        NOT NULL,
                tarih                   datetime    NOT NULL,
                PRIMARY KEY( id )
            ) ',
            'cookie_tokens' => ' (
                id                      int         AUTO_INCREMENT,
                selector                text        NOT NULL,
                token                   char(64)    NOT NULL,
                personel                text        NOT NULL,
                PRIMARY KEY( id )
            ) '
        );


        foreach( $tablolar as $key => $syntax ){
             DB::getInstance()->query("CREATE TABLE IF NOT EXISTS " .$key. $syntax );
        }

    }

    public function parca_tipi_init(){

        $sql = array(
            array(
                "isim"                              => "Balata",
                "tip"                               => Parca_Tipi::$BARKODSUZ,
                "kategori"                          => Parca_Tipi::$MEKANIK,
                "miktar_olcu_birimi"                => Parca_Tipi::$ADET,
                "ideal_degisim_sikligi_alt"         => 0,
                "ideal_degisim_sikligi_ust"         => 0,
                "ideal_degisim_sikligi_tarih_alt"   => 0,
                "ideal_degisim_sikligi_tarih_ust"   => 0,
                "kritik_seviye_limiti"              => 0,
                "varyantlar"                        => array( "Sağ" => 1, "Sol" => 1, "Arka Sol" => 0, "Arka Sağ" => 0, "Ön Sol" => 0, "Ön Sağ" => 0 )
            ),
            array(
                "isim"                              => "Yağ",
                "tip"                               => Parca_Tipi::$BARKODSUZ,
                "kategori"                          => Parca_Tipi::$SARF,
                "miktar_olcu_birimi"                => Parca_Tipi::$LITRE,
                "ideal_degisim_sikligi_alt"         => 0,
                "ideal_degisim_sikligi_ust"         => 0,
                "ideal_degisim_sikligi_tarih_alt"   => 0,
                "ideal_degisim_sikligi_tarih_ust"   => 0,
                "kritik_seviye_limiti"              => 0,
                "varyantlar"                        => array( "Motor" => 2, "Şanzıman" => 2, "Gres" => 2, "Sıvı Gres" => 2, "Diferansiyel" => 2, "Direksiyon" => 2 )
            ),
            array(
                "isim"                              => "Antifriz",
                "tip"                               => Parca_Tipi::$BARKODSUZ,
                "kategori"                          => Parca_Tipi::$SARF,
                "miktar_olcu_birimi"                => Parca_Tipi::$LITRE,
                "ideal_degisim_sikligi_alt"         => 0,
                "ideal_degisim_sikligi_ust"         => 0,
                "ideal_degisim_sikligi_tarih_alt"   => 0,
                "ideal_degisim_sikligi_tarih_ust"   => 0,
                "kritik_seviye_limiti"              => 0,
                "varyantlar"                        => array( "Antifriz" => 2 )
            ),
            array(
                "isim"                              => "Balata Spreyi",
                "tip"                               => Parca_Tipi::$BARKODSUZ,
                "kategori"                          => Parca_Tipi::$SARF,
                "miktar_olcu_birimi"                => Parca_Tipi::$ADET,
                "ideal_degisim_sikligi_alt"         => 0,
                "ideal_degisim_sikligi_ust"         => 0,
                "ideal_degisim_sikligi_tarih_alt"   => 0,
                "ideal_degisim_sikligi_tarih_ust"   => 0,
                "kritik_seviye_limiti"              => 0,
                "varyantlar"                        => array( "Balata Spreyi" => 2 )
            ),
            array(
                "isim"                              => "Silikon",
                "tip"                               => Parca_Tipi::$BARKODSUZ,
                "kategori"                          => Parca_Tipi::$SARF,
                "miktar_olcu_birimi"                => Parca_Tipi::$ADET,
                "ideal_degisim_sikligi_alt"         => 0,
                "ideal_degisim_sikligi_ust"         => 0,
                "ideal_degisim_sikligi_tarih_alt"   => 0,
                "ideal_degisim_sikligi_tarih_ust"   => 0,
                "kritik_seviye_limiti"              => 0,
                "varyantlar"                        => array( "Silikon" => 2 )
            ),
            array(
                "isim"                              => "Bant",
                "tip"                               => Parca_Tipi::$BARKODSUZ,
                "kategori"                          => Parca_Tipi::$SARF,
                "miktar_olcu_birimi"                => Parca_Tipi::$ADET,
                "ideal_degisim_sikligi_alt"         => 0,
                "ideal_degisim_sikligi_ust"         => 0,
                "ideal_degisim_sikligi_tarih_alt"   => 0,
                "ideal_degisim_sikligi_tarih_ust"   => 0,
                "kritik_seviye_limiti"              => 0,
                "varyantlar"                        => array( "Bant" => 2 )
            )
        );

        foreach( $sql as $data ){
            $Parca_Tipi = new Parca_Tipi();
            $Parca_Tipi->ekle( $data );
            if( isset($data["varyantlar"] ) ){
                foreach( $data["varyantlar"] as $varyant => $gc ){
                    $Barkodsuz_Parca = new Barkodsuz_Parca();
                    $Barkodsuz_Parca->ekle(array(
                        "isim"   => $varyant,
                        "miktar" => 0,
                        "tip"    => $Parca_Tipi->get_details("gid"),
                        "gcmod"  => $gc
                    ));
                }
            }
        }
    }

}