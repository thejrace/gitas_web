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

            case 'parca_detay':
                // sadece barkodlu parçada var
                if( in_array( Aktiviteler::PARCA_TIPI_ALT_STOK_DETAY, $KULLANICI_IZINLER ) ) {
                    $Parca = new Parca( Input::get("stok_kodu") );
                    if( $Parca->exists() ){
                        $DATA = $Parca->detay_html();
                    }

                    $TEXT = $Parca->get_return_text();
                }

            break;

            case 'parca_duzenle':


            break;


        }

        $output = json_encode(array(
            "ok"    => $OK,
            "text"  => $TEXT,
            "data"  => $DATA,
            "inputret" => $input_output, // form input errorlari
            "oh"    => $_POST
        ));
        echo $output;
        die;

    }