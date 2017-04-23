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

            case 'veri_al':

                if( Input::get("filter") != "" ){
                    $FILTER_DATA = GET_Filter::sql(Input::get("filter"));
                    $query = DB::getInstance()->query("SELECT * FROM " . DBT_ISEMRI_FORMLARI . " WHERE " . $FILTER_DATA["params"], $FILTER_DATA["vals"] . " ORDER BY tarih DESC")->results();
                } else {
                    $query = DB::getInstance()->query("SELECT * FROM " . DBT_ISEMRI_FORMLARI . " ORDER BY tarih DESC" )->results();
                }
                foreach( $query as $form ){
                    if( $form["durum"] == Is_Emri_Formu::$TASLAK ){
                        $durum = "Taslak Halinde";
                        $ico = GitasDT_CSS::$ICO_IEF_GRI;
                        $color = GitasDT_CSS::$C_BEYAZ;
                    } else {
                        $durum = "Tamamlandı";
                        $ico = GitasDT_CSS::$ICO_TICK_GRI;
                        $color = GitasDT_CSS::$C_GRI;
                    }
                    $right_content = array(
                        "text" => $form["tarih"]
                    );
                    $output = array(
                        "data_id"   => $form["gid"],
                        "ico"       => $ico, // js de tanimli
                        "bigtitle"  => $form["plaka"],
                        "subtitle"  => $durum,
                        "color"     => $color,
                        "font"      => GitasDT_CSS::$F_BOLD,
                        "kompbut"   => true,
                        "datarole"  => "formdetay"
                    );
                    $output["right_content"] = $right_content;
                    $DATA[] = $output;
                }
            break;

            case 'detay_al':

                $Form = new Is_Emri_Formu( Input::get("form_id") );
                $DATA = $Form->detay_html();

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