<?php
/**
 * Created by PhpStorm.
 * User: Jeppe
 * Date: 21.03.2017
 * Time: 17:00
 */
require '../inc/init.php';

    if( $_POST ) {

        $OK = 1;
        $TEXT = "";
        $DATA = array();
        $input_output = array();

        $INPUT_LIST = array(
            "isim"                      => array(array("req" => true), ""),
            "vergi_dairesi"             => array(array("req" => true), ""),
            "vergi_no"                  => array(array("req" => true, "numerik" => true ), ""),
            "telefon_1"                 => array(array("numerik" => true, "req" => true ), ""),
            "telefon_2"                 => array(array("numerik" => true ), ""),
            "eposta"                    => array(array("posnum" => true, "email" => true ), "")
        );

        switch (Input::get("req")) {

            case "satici_firma_ekle":

                $Validation = new Validation( new InputErrorHandler );
                // Formu kontrol et
                $Validation->check_v2( Input::escape($_POST), $INPUT_LIST );
                if( $Validation->failed() ){
                    $OK = 0;
                    $input_output = $Validation->errors()->js_format();
                    $TEXT = "Formda hatalar var.";
                } else {
                    $Firma = new Satici_Firma();
                    $Firma->ekle($_POST);
                    if( !$Firma->is_ok() ){
                        $OK = 0;
                    } else {
                        $DATA["firma_adi"] = $Firma->get_details("isim");
                        $DATA["gid"] = $Firma->get_details("gid");
                    }
                    $TEXT = $Firma->get_return_text();
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