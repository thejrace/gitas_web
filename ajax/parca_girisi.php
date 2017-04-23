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
            "aciklama"              => array(array("req" => true ), ""),
            "parca_tipi"            => array(array("req" => true ), ""),
            "garanti_suresi"        => array(array("req" => true ), "")
        );

        switch( Input::get("req") ){


            case "veri_al":

                $query = DB::getInstance()->query("SELECT * FROM " . DBT_PARCA_GIRISLERI . " ORDER BY tarih DESC")->results();
                foreach( $query as $giris ){
                    $Giris_Yapan = new Personel( $giris["giris_yapan"] );
                    $output = array(
                        "data_id"   => $giris["gid"],
                        "ico"       => GitasDT_CSS::$ICO_SEPET, // js de tanimli
                        "bigtitle"  => $giris["tarih"],
                        "subtitle"  => $Giris_Yapan->get_details("isim"),
                        "color"     => GitasDT_CSS::$C_BEYAZ,
                        "font"      => GitasDT_CSS::$F_BOLD,
                        "kompbut"   => true,
                        "datarole"  => "girisdetay"
                    );
                    $DATA[] = $output;
                }

            break;


            case "detay_al":
                $Parca_Girisi = new Parca_Girisi( Input::get("item_id") );
                if( !$Parca_Girisi->exists() ){
                    $OK = 0;
                } else {
                    $Parca_Girisi->giris_icerik_listele();
                    $DATA = $Parca_Girisi->detay_html();
                }
            break;


            case "parca_girisi":
                $Validation = new Validation( new InputErrorHandler );
                // Formu kontrol et
                $Validation->check_v2( Input::escape($_POST), $INPUT_LIST );
                if( $Validation->failed() ){
                    $OK = 0;
                    $input_output = $Validation->errors()->js_format();
                } else {
                    $TOTAL_PARCALAR = array();
                    $Parca_Tipi = new Parca_Tipi( $_POST["parca_tipi"] );
                    if( $Parca_Tipi->exists() ){
                        if( $Parca_Tipi->get_details("tip") == Parca_Tipi::$BARKODSUZ ){
                            $Barkodsuz_Parca = new Barkodsuz_Parca( $_POST["aciklama"] );
                            $Barkodsuz_Parca->add_gecici_data(array(
                                "ptip"                 => Parca_Tipi::$BARKODSUZ,
                                "eklenecek_miktar"     => $_POST["adet"],
                                "fatura_no"            => $_POST["fatura_no"],
                                "satici_firma"         => $_POST["satici_firma"]
                            ));
                            $TOTAL_PARCALAR[] = $Barkodsuz_Parca;
                        } else{
                            for( $x = 0; $x < $_POST["adet"]; $x++ ){
                                $Barkodlu_Parca = new Barkodlu_Parca();
                                $Barkodlu_Parca->set_gecici_data(array(
                                    "ptip"               => Parca_Tipi::$BARKODLU,
                                    "aciklama"          => $_POST["aciklama"],
                                    "tip"               => $_POST["parca_tipi"],
                                    "fatura_no"         => $_POST["fatura_no"],
                                    "satici_firma"      => $_POST["satici_firma"],
                                    "garanti_suresi"    => $_POST["garanti_suresi"]
                                ));
                                // direk obje olarak ekliyoruz
                                $TOTAL_PARCALAR[] = $Barkodlu_Parca;
                            }
                        }
                        $Parca_Girisi = new Parca_Girisi();
                        $Parca_Girisi->set_gid( $_POST["parca_giris_id"] );
                        $Parca_Girisi->ekle( $TOTAL_PARCALAR );
                        if( $Parca_Girisi->is_ok() ){
                            // parçalarin detayi alinacak
                            $DATA["eklenenler"] = $Parca_Girisi->get_eklenenler();
                        } else {
                            $OK = 0;
                        }
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