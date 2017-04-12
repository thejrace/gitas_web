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

            case "parca_tipi_select":

                $Parca_Tipi = new Parca_Tipi( Input::get("parca_tipi") );
                $DATA["tip"] = $Parca_Tipi->get_details("tip");


                // burada balatayı ayıklıcaz sadece sağ ve sol gelicek
                if( $Parca_Tipi->get_details("isim") == "Balata" ){
                    foreach( $Parca_Tipi->varyantlari_listele() as $varyant ){
                        if( $varyant["aciklama"] == "Sağ" || $varyant["aciklama"] == "Sol" )  $DATA["varyantlar"][] = $varyant;
                    }
                } else {
                    $DATA["varyantlar"] = $Parca_Tipi->varyantlari_listele();
                }

            break;


            case "parca_tipi_select_cikis":
                $Parca_Tipi = new Parca_Tipi( Input::get("parca_tipi") );
                $DATA["tip"] = $Parca_Tipi->get_details("tip");

                if( $Parca_Tipi->get_details("isim") == "Balata" ){
                    foreach( $Parca_Tipi->varyantlari_listele() as $varyant ){
                        if( $varyant["isim"] != "Sağ" && $varyant["isim"] != "Sol" )  $DATA["varyantlar"][] = $varyant;
                    }
                } else {
                    $DATA["varyantlar"] = $Parca_Tipi->varyantlari_listele();
                }

            break;

            case "parca_tipi_duzenle":

                $Parca_Tipi = new Parca_Tipi( Input::get("item_id") );
                $Parca_Tipi->duzenle( $_POST );
                if( !$Parca_Tipi->is_ok() ){
                    $OK = 0;
                }
                $TEXT = $Parca_Tipi->get_return_text();
            break;

            case 'parca_veri_al':

                $Parca_Tipi = new Parca_Tipi( Input::get("parca_tipi") );
                $DATA = $Parca_Tipi->parca_tablo_data();

            break;

            case 'parca_tipi_ayarlar':

                $Parca_Tipi = new Parca_Tipi( Input::get("parca_tipi") );
                $DATA = $Parca_Tipi->get_duzenle_form();

            break;


            case "parca_tipi_ekle":

                $Validation = new Validation( new InputErrorHandler );
                // Formu kontrol et
                $Validation->check_v2( Input::escape($_POST), $INPUT_LIST );
                if( $Validation->failed() ){
                    $OK = 0;
                    $input_output = $Validation->errors()->js_format();
                } else {
                    $Parca_Tipi = new Parca_Tipi();
                    $Parca_Tipi->ekle( $_POST );
                    if( $Parca_Tipi->is_ok() ){
                        if( $_POST["tip"] == Parca_Tipi::$BARKODSUZ ) {
                            // varyantlari barkodsuz parca olarak ekliyoruz
                            if( isset( $_POST["varyantlar"] ) ) {
                                foreach ($_POST["varyantlar"] as $varyant) {
                                    $Barkodsuz_Parca = new Barkodsuz_Parca();
                                    $Barkodsuz_Parca->ekle(array(
                                        "aciklama"   => $varyant,
                                        "miktar"     => 0,
                                        "tip"        => $Parca_Tipi->get_details("gid"),
                                        "gcmod"      => $Barkodsuz_Parca::$GC
                                    ));
                                }
                            }
                        }
                        $DATA["stok_kodu"] = $Parca_Tipi->get_details("gid");
                        $DATA["isim"] = $Parca_Tipi->get_details("isim");
                    } else {
                        $OK = 0;
                    }
                    $TEXT = $Parca_Tipi->get_return_text();
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