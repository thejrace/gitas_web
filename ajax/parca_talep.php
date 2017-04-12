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
            "form_gid" 			=> array( array( "req" => true )  ,"" ),
            "parca_tipi" 		=> array( array( "req" => true, "not_zero" => true )  ,"" ),
            "adet" 			    => array( array( "req" => true, "not_zero" => true )  ,"" ),
            "aciklama" 	        => array( array( "req" => true )  ,"" )
        );

        switch( Input::get("req") ){


            case "parca_talep":

                $Validation = new Validation( new InputErrorHandler );
                // Formu kontrol et
                $Validation->check_v2( Input::escape($_POST), $INPUT_LIST );
                if( $Validation->failed() ){
                    $OK = 0;
                    $input_output = $Validation->errors()->js_format();
                } else {
                    $Parca_Talebi = new Parca_Talebi();
                    $Parca_Talebi->ekle(array(
                        "form_gid"              => Input::get("form_id"),
                        "parca_tipi"            => Input::get("parca_tipi"),
                        "adet"                  => Input::get("miktar"),
                        "aciklama"              => Input::get("aciklama")
                    ));
                    if( !$Parca_Talebi->is_ok() ){
                        $Ok = 0;
                    }
                    $TEXT = $Parca_Talebi->get_return_text();
                }

            break;

            case "teklif_yap":

                /*$Parca_Talebi->teklif_ekle(array(
                    "talep_gid"             => $Parca_Talebi->get_details("gid"),
                    "firma"                 => "REV FIRMA",
                    "aciklama"              => "10 parçada %3 iskonto",
                ));*/


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