<?php

	require 'defs.php';

    ini_set('error_log',  MAIN_DIR . "error.log");

	// Otomatik class include
	function autoload_main_classes($class_name){
		$file = CLASS_DIR . $class_name. '.php';
	    if (file_exists($file)) require_once($file);
	}
	spl_autoload_register( 'autoload_main_classes' );

	Session::start();

	//$DBSETUP = new DBSetup();
	//$DBSETUP->tablolari_olustur();

    $Auto_Login = new Auto_Login;
    if( $Auto_Login->check() ) {
        $Login = new Login;
        $Login->auto_action($Auto_Login->get_user_id());
    } else{
        if( !isset($GIRIS_FLAG) ){
            header("Location: giris.php");
            //die;
        }
    }

	class Aktiviteler {

	    const   IS_EMRI_FORMU_EKLEME                = 1,
                IS_EMRI_FORMLARI_DT                 = 2,
                IS_EMRI_FORMU_DETAY                 = 3,
                OTOBUSLER_DT                        = 4,
                OTOBUS_SURUCU_DETAY_INCELEME        = 5, // otobus - surucu istatistik
                OTOBUS_ISTATISTIK_INCELEME          = 6,
                OTOBUS_IS_EMRI_FORMU_INCELEME       = 7, // otobus - isemri formu ( is emri formlarina erisim yoksa buton cikmicak )
                OTOBUS_DETAY_INCELEME               = 8,
                OTOBUS_AYARLAR                      = 9,
                STOK_DT                             = 10,
                PARCA_TIPI_ISTATISTIK               = 11,
                PARCA_TIPI_TALEP                    = 12,
                PARCA_TIPI_AYARLAR                  = 13,
                PARCA_TIPI_STOK_DETAY               = 14, // dt deki artı butonu
                PARCA_TIPI_ALT_STOK_DETAY           = 15, // parça inceleme buyutec
                REVIZYON_TALEPLERI_DT               = 16,
                REVIZYON_TALEP_TEKLIF_EKLEME        = 17,
                REVIZYON_TALEP_ONAYLAMA             = 18,
                REVIZYON_TALEP_INCELEME             = 19,
                REVIZYON_TALEP_EKLEME               = 20,
                PARCA_GIRISI                        = 21,
                PARCA_GIRIS_DETAY                   = 22,
                PARCA_TIPI_EKLEME                   = 23,
                SATICI_FIRMA_EKLEME                 = 24,
                SURUCULER_DT                        = 25,
                SURUCU_DETAY_INCELEME               = 26,
                SURUCU_AYARLAR                      = 27,
                PARCA_GIRISLERI_DT                  = 28,
                PARCA_TIPI_EKLE                     = 29,
                PARCA_TALEP_EKLEME                  = 30,
                PARCA_TALEP_KAPAMA                  = 31,
                VARYANTLAR_DT                       = 32;

	    // 0 admin
	    // 1 servis
        // 2 muhasebe
        // 3 surucu
	    public static $IZINLER = array(
	        0 => array(
                self::IS_EMRI_FORMU_EKLEME,
                self::IS_EMRI_FORMLARI_DT,
                self::IS_EMRI_FORMU_DETAY,
                self::OTOBUSLER_DT,
                self::OTOBUS_SURUCU_DETAY_INCELEME,
                self::OTOBUS_ISTATISTIK_INCELEME,
                self::OTOBUS_IS_EMRI_FORMU_INCELEME,
                self::OTOBUS_DETAY_INCELEME,
                self::OTOBUS_AYARLAR,
                self::STOK_DT,
                self::PARCA_TIPI_ISTATISTIK,
                self::PARCA_TIPI_TALEP,
                self::PARCA_TIPI_AYARLAR,
                self::PARCA_TIPI_STOK_DETAY,
                self::PARCA_TIPI_ALT_STOK_DETAY,
                self::REVIZYON_TALEPLERI_DT,
                self::REVIZYON_TALEP_TEKLIF_EKLEME,
                self::REVIZYON_TALEP_ONAYLAMA,
                self::REVIZYON_TALEP_INCELEME,
                self::REVIZYON_TALEP_EKLEME,
                self::PARCA_GIRISI,
                self::PARCA_GIRIS_DETAY,
                self::PARCA_TIPI_EKLEME,
                self::SATICI_FIRMA_EKLEME,
                self::SURUCULER_DT,
                self::SURUCU_DETAY_INCELEME,
                self::SURUCU_AYARLAR,
                self::PARCA_GIRISLERI_DT,
                self::PARCA_TIPI_EKLE,
                self::PARCA_TALEP_EKLEME,
                self::PARCA_TALEP_KAPAMA,
                self::VARYANTLAR_DT
            ),
	        1 => array(
	            self::IS_EMRI_FORMU_EKLEME,
                self::IS_EMRI_FORMLARI_DT,
                self::IS_EMRI_FORMU_DETAY,
                self::OTOBUSLER_DT,
                self::OTOBUS_ISTATISTIK_INCELEME,
                self::OTOBUS_IS_EMRI_FORMU_INCELEME,
                self::OTOBUS_DETAY_INCELEME,
                self::STOK_DT,
                self::PARCA_TIPI_ISTATISTIK,
                self::PARCA_TIPI_TALEP,
                self::PARCA_TIPI_STOK_DETAY,
                self::PARCA_TIPI_ALT_STOK_DETAY,
                self::REVIZYON_TALEPLERI_DT,
                self::REVIZYON_TALEP_INCELEME,
                self::REVIZYON_TALEP_EKLEME
            )
        );
    }
    if( Active_User::get_details("seviye") != "" ){
        $KULLANICI_IZINLER = Aktiviteler::$IZINLER[Active_User::get_details("seviye")];
    } else {
        //exit;
    }



