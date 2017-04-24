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
            "aktif_kapi_kodu"   => array(array("req" => true), ""),
            "ogs"               => array(array("req" => true, "numerik" => true ), ""),
            "model_yili"        => array(array("req" => true ), ""),
            "marka"             => array(array("req" => true ), ""),
            "model"             => array(array("req" => true ), "")
        );

        switch( Input::get("req") ){


            // is emri formundan arama
            case "otobus_detay":
                $Otobus = new Otobus(Input::get("plaka"));
                if ($Otobus->exists()) {
                    $DATA = $Otobus->get_details();

                    if (Input::get("form_gid") != "") {
                        $Form = new Is_Emri_Formu();
                        // form eklenirken form inputu gibi gondericez
                        $DATA["form_gid"] = $Form->gid_olustur(Input::get("plaka"));
                    }

                } else {
                    $OK = 0;
                }
                $TEXT = $Otobus->get_return_text();
            break;

            case "veri_al":

                if( in_array( Aktiviteler::OTOBUSLER_DT, $KULLANICI_IZINLER ) ) {
                    $query = DB::getInstance()->query("SELECT * FROM " . DBT_OTOBUSLER)->results();
                    foreach ($query as $otobus) {
                        if ($otobus["durum"] == Otobus::$SERVIS) {
                            $color = GitasDT_CSS::$C_KIRMIZI;
                        } else {
                            $color = GitasDT_CSS::$C_BEYAZ;
                        }
                        // todo otobus servis uyarilarini burada kontrol edip yeni bi icon set seklinde gonderebilirim
                        $output = array(
                            "data_id" => $otobus["plaka"],
                            "ico" => GitasDT_CSS::$ICO_OTOBUS,
                            "icoset" => GitasDT_CSS::$ICOSET_OTOBUS,
                            "bigtitle" => $otobus["plaka"],
                            "subtitle" => $otobus["aktif_kapi_kodu"],
                            "color" => $color,
                            "font" => GitasDT_CSS::$F_BOLD
                        );
                        $DATA[] = $output;
                    }
                }

            break;

            case "detay_al":
                if( in_array( Aktiviteler::OTOBUS_DETAY_INCELEME, $KULLANICI_IZINLER ) ) {
                    $Otobus = new Otobus(Input::get("item_id"));
                    $DATA = $Otobus->detay_html();
                }

            break;

            case "ayarlar":
                if( in_array( Aktiviteler::OTOBUS_AYARLAR, $KULLANICI_IZINLER ) ) {
                    $Otobus = new Otobus(Input::get("item_id"));
                    $DATA = $Otobus->ayarlar_html();
                }
            break;

            case "ayarlar_form":
                if( in_array( Aktiviteler::OTOBUS_AYARLAR, $KULLANICI_IZINLER ) ) {
                    $Validation = new Validation(new InputErrorHandler);
                    // Formu kontrol et
                    $Validation->check_v2(Input::escape($_POST), $INPUT_LIST);
                    if ($Validation->failed()) {
                        $OK = 0;
                        $input_output = $Validation->errors()->js_format();
                    } else {
                        $Otobus = new Otobus(Input::get("item_id"));
                        $Otobus->duzenle($_POST);
                        if (!$Otobus->is_ok()) {
                            $OK = 0;
                        }
                        $TEXT = $Otobus->get_return_text();
                    }
                }
            break;


            case 'stats':
                if( in_array( Aktiviteler::OTOBUS_ISTATISTIK_INCELEME, $KULLANICI_IZINLER ) ) {
                    $Otobus = new Otobus(Input::get("item_id"));
                    $Otobus->stats_init();
                    $DATA = $Otobus->stats_html();
                }
            break;

        }

        $output = json_encode(array(
            "ok"    => $OK,
            "text"  => $TEXT,
            "data"  => $DATA,
            "oh"    => $_POST
        ));
        echo $output;
        die;

    }