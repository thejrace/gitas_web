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

        switch( Input::get("req") ){






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
//                $DATA = array(
//
//                    array(
//                        "data_id" => "GTSPATIPBALATA",
//                        "ico" => GitasDT_CSS::$ICO_PARCA_TIPI, // js de tanimli
//                        "bigtitle" => "BANT",
//                        "subtitle" => "100 Adet",
//                        "color" => GitasDT_CSS::$C_BEYAZ,
//                        "font"  => GitasDT_CSS::$F_BOLD,
//                        "icoset" => GitasDT_CSS::$ICOSET_PARCA_TIPI, // parcatipi
//                        "part2"  => true
//                    ),
//                    array(
//                        "data_id" => "GTSPATIPBALATA",
//                        "ico" => GitasDT_CSS::$ICO_PARCA_TIPI, // js de tanimli
//                        "bigtitle" => "AMOREY",
//                        "subtitle" => "100 Adet",
//                        "color" => GitasDT_CSS::$C_BEYAZ,
//                        "font"  => GitasDT_CSS::$F_BOLD,
//                        "icoset" => GitasDT_CSS::$ICOSET_PARCA_TIPI, // parcatipi
//                        "part2"  => true
//                    ),
//                    array(
//                        "data_id" => "GTSPATIPBALATA",
//                        "ico" => GitasDT_CSS::$ICO_PARCA_TIPI,
//                        "bigtitle" => "KALİPER",
//                        "subtitle" => "100 Adet",
//                        "color" => GitasDT_CSS::$C_BEYAZ,
//                        "font"  => GitasDT_CSS::$F_BOLD,
//                        "icoset" => GitasDT_CSS::$ICOSET_PARCA_TIPI, // parça tipi --> js de taniyip iconlari yerlestircez veri azalsin
//                        "right_content" => array(
//                            "ico" => GitasDT_CSS::$ICO_WARNING1,
//                            "text" => "Stok Kritik Seviyede"
//                        )
//                    )
//
//                );