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

        switch (Input::get("req")) {

            case 'veri_al':

                if( in_array( Aktiviteler::IS_EMRI_FORMLARI_DT, $KULLANICI_IZINLER ) ) {
                    if (Input::get("filter") != "") {
                        $FILTER_DATA = GET_Filter::sql(Input::get("filter"));
                        $query = DB::getInstance()->query("SELECT * FROM " . DBT_ISEMRI_FORMLARI . " WHERE " . $FILTER_DATA["params"] . " ORDER BY tarih DESC", $FILTER_DATA["vals"])->results();
                    } else {
                        $query = DB::getInstance()->query("SELECT * FROM " . DBT_ISEMRI_FORMLARI . " ORDER BY tarih DESC")->results();
                    }
                    foreach ($query as $form) {
                        if ($form["durum"] == Is_Emri_Formu::$TASLAK) {
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
                            "data_id" => $form["gid"],
                            "ico" => $ico, // js de tanimli
                            "bigtitle" => $form["plaka"],
                            "subtitle" => $durum,
                            "color" => $color,
                            "font" => GitasDT_CSS::$F_BOLD,
                            "kompbut" => true,
                            "datarole" => "formdetay"
                        );
                        $output["right_content"] = $right_content;
                        $DATA[] = $output;
                    }
                }


            break;

            case 'detay_al':

                if( in_array( Aktiviteler::IS_EMRI_FORMU_DETAY, $KULLANICI_IZINLER ) ) {
                    $Form = new Is_Emri_Formu(Input::get("form_id"));
                    $DATA = $Form->detay_html();
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
