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