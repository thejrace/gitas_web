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
            "adet"                  => array(array("req" => true), ""),
            "fatura_no"             => array(array("req" => true), ""),
            "satici_firma"          => array(array("req" => true ), ""),
            "aciklama"              => array(array(), ""),
            "parca_tipi"            => array(array("req" => true ), ""),
            "garanti_baslangic"     => array(array(), ""),
            "garanti_suresi"        => array(array(), "")
        );

        switch( Input::get("req") ){


            case "veri_al":
                if( in_array( Aktiviteler::PARCA_GIRISLERI_DT, $KULLANICI_IZINLER ) ) {
                    $query = DB::getInstance()->query("SELECT * FROM " . DBT_PARCA_GIRISLERI . " ORDER BY tarih DESC")->results();
                    foreach ($query as $giris) {
                        $Giris_Yapan = new Personel($giris["giris_yapan"]);
                        $output = array(
                            "data_id" => $giris["gid"],
                            "ico" => GitasDT_CSS::$ICO_SEPET, // js de tanimli
                            "bigtitle" => $giris["tarih"],
                            "subtitle" => $Giris_Yapan->get_details("isim"),
                            "color" => GitasDT_CSS::$C_BEYAZ,
                            "font" => GitasDT_CSS::$F_BOLD,
                            "kompbut" => true,
                            "datarole" => "girisdetay"
                        );
                        $DATA[] = $output;
                    }
                }

            break;


            case "detay_al":
                if( in_array( Aktiviteler::PARCA_GIRIS_DETAY, $KULLANICI_IZINLER ) ) {
                    $Parca_Girisi = new Parca_Girisi(Input::get("item_id"));
                    if (!$Parca_Girisi->exists()) {
                        $OK = 0;
                    } else {
                        $Parca_Girisi->giris_icerik_listele();
                        $DATA = $Parca_Girisi->detay_html();
                    }
                }
            break;


            case "parca_girisi":

                if( in_array( Aktiviteler::PARCA_GIRISI, $KULLANICI_IZINLER ) ) {
                    $Validation = new Validation(new InputErrorHandler);
                    // Formu kontrol et
                    $Validation->check_v2(Input::escape($_POST), $INPUT_LIST);
                    if ($Validation->failed()) {
                        $OK = 0;
                        $input_output = $Validation->errors()->js_format();
                    } else {

                        $Parca_Girisi = new Parca_Girisi();
                        $Parca_Girisi->set_gid($_POST["parca_giris_gid"]);
                        $Parca_Girisi->ekle( $_POST );
                        $DATA["eklenenler"] = $Parca_Girisi->get_eklenenler();

                    }
                }

            break;

        }

        $output = json_encode(array(
            "ok"        => $OK,
            "text"      => $TEXT,
            "data"      => $DATA,
            "oh"        => $_POST,
            "inputret"  => $input_output
        ));
        echo $output;
        die;

    }