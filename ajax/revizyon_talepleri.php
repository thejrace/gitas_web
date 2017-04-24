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

            case "veri_al":
                if( in_array( Aktiviteler::REVIZYON_TALEPLERI_DT, $KULLANICI_IZINLER ) ) {
                    $query = DB::getInstance()->query("SELECT * FROM " . DBT_REVIZYON_TALEPLERI . " ORDER BY durum DESC, tarih DESC")->results();
                    foreach ($query as $talep) {
                        $Parca = new Barkodlu_Parca($talep["stok_kodu"]);
                        $Parca_Tipi = new Parca_Tipi($Parca->get_details("tip"));
                        $output = array(
                            "data_id" => $talep["gid"],
                            "bigtitle" => $Parca_Tipi->get_details("isim") . " ( " . $talep["tarih"] . " )",
                            "color" => $color = GitasDT_CSS::$C_BEYAZ,
                            "font" => GitasDT_CSS::$F_BOLD
                        );
                        if ($talep["durum"] == Revizyon_Talebi::$AKTIF) {
                            $output["ico"] = GitasDT_CSS::$ICO_IEF_GRI;
                            $output["icoset"] = GitasDT_CSS::$ICOSET_REVIZYON_TALEP;
                            $output["subtitle"] = "Aktif";
                        } else if ($talep["durum"] == Revizyon_Talebi::$TAMAMLANDI) {
                            $Personel = new Personel($talep["ilgili_personel"]);
                            $output["ico"] = GitasDT_CSS::$ICO_TICK_GRI;
                            $output["subtitle"] = "Tamamlandı ( " . $Personel->get_details("isim") . " )";
                            $output["right_content"] = array("text" => $talep["tamamlanma_tarihi"]);
                            $output["kompbut"] = true;
                            $output["datarole"] = "revtamamdetay";
                        } else {
                            // teklif onayi bekleniyor
                            $output["ico"] = GitasDT_CSS::$ICO_IEF_YESIL;
                            $output["icoset"] = GitasDT_CSS::$ICOSET_REVIZYON_TALEP;
                            $output["subtitle"] = "Teklif onayı bekleniyor.";
                        }
                        $DATA[] = $output;
                    }
                }
            break;

            case "revizyon_teklifi_ekle":
                if( in_array( Aktiviteler::REVIZYON_TALEP_EKLEME, $KULLANICI_IZINLER ) ) {
                    $RevTalep = new Revizyon_Talebi(Input::get("talep_gid"));
                    if (!$RevTalep->exists()) {
                        $Ok = 0;
                    } else {
                        $RevTalep->teklif_ekle($_POST);
                        if (!$RevTalep->is_ok()) $OK = 0;
                    }
                    $TEXT = $RevTalep->get_return_text();
                }
            break;

            case "talep_inceleme":
                if( in_array( Aktiviteler::REVIZYON_TALEP_INCELEME, $KULLANICI_IZINLER ) ) {

                }
            break;

            case "barkod_arama":
                if( in_array( Aktiviteler::REVIZYON_TALEPLERI_DT, $KULLANICI_IZINLER ) ) {
                    $Parca = new Barkodlu_Parca(Input::get("stok_kodu"));
                    if ($Parca->exists()) $DATA = $Parca->revizyon_taleplerini_listele();
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