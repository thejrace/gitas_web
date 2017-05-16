<?php
/**
 * Created by PhpStorm.
 * User: Jeppe
 * Date: 21.03.2017
 * Time: 17:00
 */
    require '../inc/init.php';

    if( $_POST ){

        $OK = 1;
        $TEXT = "";
        $DATA = array();
        $input_output = array();

        $INPUT_LIST = array(
            "isim" 					            => array( array( "req" => true )  ,"" ),
            "kategori" 				            => array( array( "req" => true, "not_zero" => true )  ,"" ),
            "tip" 			                    => array( array( "req" => true, "not_zero" => true )  ,"" ),
            "miktar_olcu_birimi" 	            => array( array( "posnum" => true )  ,"" ),
            "ideal_degisim_sikligi_alt" 	    => array( array( "posnum" => true )  ,"" ),
            "ideal_degisim_sikligi_ust" 	    => array( array( "posnum" => true )  ,"" ),
            "ideal_degisim_sikligi_tarih_alt" 	=> array( array( "posnum" => true )  ,"" ),
            "ideal_degisim_sikligi_tarih_ust" 	=> array( array( "posnum" => true )  ,"" ),
            "kritik_seviye_limiti" 	            => array( array( "posnum" => true )  ,"" )
        );

        switch( Input::get("req") ){

            case "veri_al":
                if( in_array( Aktiviteler::STOK_DT, $KULLANICI_IZINLER ) ) {
                    $query = DB::getInstance()->query("SELECT * FROM " . DBT_PARCA_TIPLERI)->results();
                    foreach ($query as $parca_tipi) {
                        $right_content_var = false;
                        $color = GitasDT_CSS::$C_BEYAZ;
                        $miktar = 0;
                        $miktar_str = "";

                        $stok_query = DB::getInstance()->query("SELECT * FROM " . DBT_PARCALAR . " WHERE parca_tipi = ? && durum = ?", array($parca_tipi["gid"], Parca_Tipi::$AKTIF))->results();
                        foreach( $stok_query as $parca ){
                            $miktar += $parca["miktar"];
                            $miktar_str = $miktar . " " . $parca_tipi["miktar_olcu_birimi"];
                        }

                        if ($miktar <= 0 || ($parca_tipi["kritik_seviye_limiti"] > 0 && $parca_tipi["kritik_seviye_limiti"] > $miktar)) {
                            $color = GitasDT_CSS::$C_KIRMIZI;
                            $right_content_var = true;
                            $right_content = array(
                                "ico" => GitasDT_CSS::$ICO_WARNING1,
                                "text" => "Stok Kritik Seviyede"
                            );
                        }
                        if( $parca_tipi["varyantli"] == 1 ){
                            $icoset = GitasDT_CSS::$ICOSET_PARCA_TIPI;
                        } else {
                            $icoset = GitasDT_CSS::$ICOSET_PARCA_TIPI_ARTISIZ;
                        }
                        $output = array(
                            "data_id" => $parca_tipi["gid"],
                            "ico" => GitasDT_CSS::$ICO_PARCA_TIPI, // js de tanimli
                            "bigtitle" => $parca_tipi["isim"],
                            "subtitle" => $miktar_str,
                            "color" => $color,
                            "font" => GitasDT_CSS::$F_BOLD,
                            "icoset" => $icoset, // parcatipi,
                            "part2" => true
                        );
                        if ($right_content_var) $output["right_content"] = $right_content;
                        $DATA[] = $output;
                    }
                }
            break;

            case 'parca_tipi_ayarlar':
                if( in_array( Aktiviteler::PARCA_TIPI_AYARLAR, $KULLANICI_IZINLER ) ) {
                    $Parca_Tipi = new Parca_Tipi(Input::get("parca_tipi"));
                    $DATA = $Parca_Tipi->get_duzenle_form();
                }
            break;

            case "parca_tipi_duzenle":
                if( in_array( Aktiviteler::PARCA_TIPI_AYARLAR, $KULLANICI_IZINLER ) ) {
                    $Parca_Tipi = new Parca_Tipi(Input::get("item_id"));
                    $Parca_Tipi->duzenle($_POST);
                    if (!$Parca_Tipi->is_ok()) {
                        $OK = 0;
                    }
                    $TEXT = $Parca_Tipi->get_return_text();
                }
            break;


            case 'parca_veri_al':
                if( in_array( Aktiviteler::PARCA_TIPI_STOK_DETAY, $KULLANICI_IZINLER ) ) {
                    $Parca_Tipi = new Parca_Tipi(Input::get("parca_tipi"));
                    $DATA = $Parca_Tipi->parca_tablo_data();
                }
                break;

            case "parca_tipi_ekle":
                if( in_array( Aktiviteler::PARCA_TIPI_EKLE, $KULLANICI_IZINLER ) ) {
                    $Validation = new Validation(new InputErrorHandler);
                    // Formu kontrol et
                    $Validation->check_v2(Input::escape($_POST), $INPUT_LIST);
                    if ($Validation->failed()) {
                        $OK = 0;
                        $input_output = $Validation->errors()->js_format();
                    } else {
                        $Parca_Tipi = new Parca_Tipi();
                        $Parca_Tipi->ekle($_POST);
                        if ($Parca_Tipi->is_ok()) {
                            $DATA["gid"] = $Parca_Tipi->get_details("gid");
                            $DATA["isim"] = $Parca_Tipi->get_details("isim");
                        } else {
                            $OK = 0;
                        }
                        $TEXT = $Parca_Tipi->get_return_text();
                    }
                }
            break;

            case "parca_tipi_select_giris":
                $Parca_Tipi = new Parca_Tipi( Input::get("parca_tipi") );
                if( $Parca_Tipi->exists() ){
                    $DATA["varyantlar"] = $Parca_Tipi->varyantlari_listele( 1 );
                } else {
                    $OK = 0;
                }
                $TEXT = $Parca_Tipi->get_return_text();
            break;

            case "barkodsuz_varyant_stok_kodu_listele":
                $Parca_Tipi = new Parca_Tipi( Input::get("parca_tipi") );
                if( $Parca_Tipi->exists() ){
                    $DATA["varyantlar"] = $Parca_Tipi->barkodsuz_varyantlari_parca_olarak_listele();
                } else {
                    $Ok = 0;
                }
                $TEXT = $Parca_Tipi->get_return_text();
            break;

            case "girisleri_listele":
                if( in_array( Aktiviteler::PARCA_TIPI_ISTATISTIK, $KULLANICI_IZINLER ) ) {
                    $Parca_Tipi = new Parca_Tipi(Input::get("patip"));
                    if (!$Parca_Tipi->exists()) {
                        $OK = 0;
                    } else {
                        foreach ($Parca_Tipi->get_girisler() as $giris_gid => $parca) {
                            $right_content = array(
                                "text" => $parca["tarih"]
                            );
                            $output = array(
                                "data_id" => $giris_gid,
                                "ico" => GitasDT_CSS::$ICO_SEPET,
                                "bigtitle" => $parca["miktar"] . " " . $Parca_Tipi->get_details("miktar_olcu_birimi"),
                                "subtitle" => $parca["giris_yapan"],
                                "color" => GitasDT_CSS::$C_YESIL,
                                "font" => GitasDT_CSS::$F_BOLD,
                                "kompbut" => true,
                                "datarole" => "girisdetay"
                            );
                            $output["right_content"] = $right_content;
                            $DATA[] = $output;
                        }
                    }
                }

            break;

            case "cikislari_listele":
                if( in_array( Aktiviteler::PARCA_TIPI_ISTATISTIK, $KULLANICI_IZINLER ) ) {
                    $Parca_Tipi = new Parca_Tipi(Input::get("patip"));
                    if (!$Parca_Tipi->exists()) {
                        $OK = 0;
                    } else {
                        foreach ($Parca_Tipi->get_cikislar() as $form_gid => $form) {
                            $right_content = array(
                                "text" => $form["tarih"]
                            );
                            $output = array(
                                "data_id" => $form_gid,
                                "ico" => GitasDT_CSS::$ICO_PARCA_TIPI,
                                "bigtitle" => $form["miktar"] . " " . $Parca_Tipi->get_details("miktar_olcu_birimi"),
                                "subtitle" => $form["plaka"],
                                "color" => GitasDT_CSS::$C_BEYAZ,
                                "font" => GitasDT_CSS::$F_BOLD,
                                "kompbut" => true,
                                "datarole" => "cikisdetay"
                            );
                            $output["right_content"] = $right_content;
                            $DATA[] = $output;
                        }
                    }
                }

                break;


            case "otobus_istatistik":
                if( in_array( Aktiviteler::PARCA_TIPI_ISTATISTIK, $KULLANICI_IZINLER ) ) {
                    $Parca_Tipi = new Parca_Tipi(Input::get("patip"));
                    if (!$Parca_Tipi->exists()) {
                        $OK = 0;
                    } else {
                        foreach ($Parca_Tipi->otobus_istatistik() as $plaka => $miktar) {
                            $output = array(
                                "data_id" => $plaka,
                                "ico" => GitasDT_CSS::$ICO_OTOBUS,
                                "bigtitle" => $miktar . " " . $Parca_Tipi->get_details("miktar_olcu_birimi"),
                                "subtitle" => $plaka,
                                "color" => GitasDT_CSS::$C_BEYAZ,
                                "font" => GitasDT_CSS::$F_BOLD,
                                "icoset" => GitasDT_CSS::$ICOSET_PATIP_OTOBUS_ISTATISTIK,
                                "kompbut" => true,
                                "datarole" => "otobusdetay",
                                "part2" => true
                            );
                            $DATA[] = $output;
                        }
                    }
                }

                break;

            case "otobus_degisim_plan":
                if( in_array( Aktiviteler::PARCA_TIPI_ISTATISTIK, $KULLANICI_IZINLER ) ) {
                    $Parca_Tipi = new Parca_Tipi(Input::get("patip"));
                    if (!$Parca_Tipi->exists()) {
                        $OK = 0;
                    } else {
                        $DATA = $Parca_Tipi->otobus_degisim_plan(Input::get("plaka"));
                    }
                }

                break;

            case "surucu_istatistik":
                if( in_array( Aktiviteler::PARCA_TIPI_ISTATISTIK, $KULLANICI_IZINLER ) ) {
                    $Parca_Tipi = new Parca_Tipi(Input::get("patip"));
                    if (!$Parca_Tipi->exists()) {
                        $OK = 0;
                    } else {
                        foreach ($Parca_Tipi->surucu_istatistik() as $surucu => $miktar) {
                            $Surucu = new Personel($surucu);
                            $output = array(
                                "data_id" => $surucu,
                                "ico" => GitasDT_CSS::$ICO_SURUCUBEYAZ,
                                "bigtitle" => $miktar . " " . $Parca_Tipi->get_details("miktar_olcu_birimi"),
                                "subtitle" => $Surucu->get_details("isim"),
                                "color" => GitasDT_CSS::$C_BEYAZ,
                                "font" => GitasDT_CSS::$F_BOLD
                            );
                            $DATA[] = $output;
                        }
                    }
                }
                break;


        }

        $output = json_encode(array(
            "ok"        => $OK,
            "text"      => $TEXT,
            "data"      => $DATA,
            "inputret"  => $input_output, // form input errorlari
            "oh"        => $_POST
        ));
        echo $output;
        die;

    }