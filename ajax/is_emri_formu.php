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


        case "onceki_parca_girisleri":

            $Parca_Tipi = new Parca_Tipi( Input::get("parca_tipi") );
            $DATA = $Parca_Tipi->otobus_onceki_girisler( Input::get("plaka"), $_POST["varyant_gid"] );
            $TEXT = $Parca_Tipi->get_return_text();

        break;

        case "parca_barkod_kontrol":

            $Parca = new Parca( Input::get("barkod") );
            if( $Parca->exists() && $Parca->get_details("durum") == Parca_Tipi::$AKTIF ){
                $Parca_Tipi = new Parca_Tipi( $Parca->get_details("parca_tipi") );
                if( $Parca_Tipi->get_details("isim") == $_POST["parca_tipi"] && $Parca_Tipi->get_details("tip") == Parca_Tipi::$BARKODLU ){
                    $Parca_Giris = new Parca_Girisi( $Parca->get_details("parca_giris_gid") );
                    $Firma = new Satici_Firma( $Parca->get_details("satici_firma") );
                    $varyant = Data_Out::$BOS;
                    $varyant_gid = Data_Out::$BOS;
                    if( $Parca_Tipi->get_details("varyantli") != Data_Out::$BOS ){
                        $Varyant = new Varyant($Parca->get_details("varyant_gid"));
                        $varyant = $Varyant->get_details("isim");
                        $varyant_gid = $Parca->get_details("varyant_gid");
                    }
                    $DATA["parca"] = array(
                        "parca_tipi" => $Parca_Tipi->get_details("isim") ,
                        "varyant"    => $varyant,
                        "varyant_gid"  => $varyant_gid,
                        "aciklama"   => $Parca->get_details("aciklama"),
                        "firma"      => $Firma->get_details("isim"),
                        "tarih"      => $Parca_Giris->get_details("tarih")
                    );
                    $cikis_varyantlar = $Parca_Tipi->varyantlari_listele( 0 );
                    if( count($cikis_varyantlar) > 0 ) $DATA["cikis_varyantlar"] = $cikis_varyantlar;
                } else {
                    $OK = 0;
                }
            } else {
                $OK = 0;
            }
            $TEXT = $Parca->get_return_text();



        break;

        case "is_emri_formu_ekle":

            if( in_array( Aktiviteler::IS_EMRI_FORMU_EKLEME, $KULLANICI_IZINLER ) ) {
                $INPUT = json_decode($_POST["FORMDATA"], true);

                $form_detaylar_input = array(
                    "plaka"             => $INPUT["plaka"],
                    "aktif_kapi_no"     => $INPUT["aktif_kapi_no"],
                    "gelis_km"          => $INPUT["gelis_km"],
                    "surucu"            => $INPUT["surucu"],
                    "gelis_tarih"       => $INPUT["gelis_tarih"],
                    "cikis_tarih"       => $INPUT["cikis_tarih"],
                    "sikayet"           => $INPUT["sikayet"],
                    "ariza_tespit"      => $INPUT["ariza_tespit"],
                    "yapilan_onarim"    => $INPUT["onarim"],
                    "durum"             => $INPUT["durum"]
                );

                ( isset($INPUT["kalibrasyon_yapildi"] ) ) ? $form_detaylar_input["kalibrasyon_yapildi"] = 1 : $form_detaylar_input["kalibrasyon_yapildi"] = 0;
                ( isset($INPUT["arac_yikama"] ) ) ? $form_detaylar_input["arac_yikama"] = 1 : $form_detaylar_input["arac_yikama"] = 0;

                $Form = new Is_Emri_Formu();
                $Form->ekle( $form_detaylar_input, $INPUT["personel_detay"], $INPUT["girenler"], $INPUT["cikanlar"] );
                if( !$Form->is_ok() ){
                    $OK = 0;
                }
                $TEXT = $Form->get_return_text();
            }

        break;

        case "is_emri_formu_ekle_eski":

            $cikanlar = array();
            $girenler = array();
            $personel_data = array();
            $form_detaylar_input = array(
                "plaka"             => Input::get("plaka"),
                "form_gid"          => Input::get("form_gid"),
                "aktif_kapi_no"     => Input::get("aktif_kapi_no"),
                "gelis_km"          => Input::get("gelis_km"),
                "surucu"            => Input::get("surucu"),
                "gelis_tarih"       => Input::get("gelis_tarih"),
                "cikis_tarih"       => Input::get("cikis_tarih"),
                "sikayet"           => Input::get("sikayet"),
                "ariza_tespit"      => Input::get("ariza_tespit"),
                "yapilan_onarim"    => Input::get("yapilan_onarim"),
                "durum"             => Input::get("durum")
            );
            $parcala_personel = explode("#", substr( $_POST["ps"], 1, -1) );
            $parcala_cikanlar = explode("#", substr( $_POST["ck"], 1, -1) );
            $parcala_girenler = explode("#", substr( $_POST["gr"], 1, -1) );
            foreach( $parcala_personel as $pers ){
                $parcala = explode("$", $pers);
                if( count($parcala) == 0 ) continue;
                $personel_data[] = array(
                    "personel"      => $parcala[0],
                    "is_tanimi"     => $parcala[1],
                    "baslama"       => $parcala[2],
                    "bitis"         => $parcala[3]
                );
            }
            foreach( $parcala_girenler as $malzeme ){
                $malz_parcala = explode("$", $malzeme );
                if( count($malz_parcala) == 0 ) continue;
                if( $malz_parcala[0] == Parca_Tipi::$BARKODSUZ ){
                    $girenler[] = array(
                        "tip" => Parca_Tipi::$BARKODSUZ,
                        "stok_kodu" => $malz_parcala[1],
                        "miktar" => $malz_parcala[2]
                    );
                } else {
                    $girenler[] = array(
                        "tip" => Parca_Tipi::$BARKODLU,
                        "stok_kodu" => $malz_parcala[1]
                    );
                }
            }
            if( !(count( $parcala_cikanlar ) == 1 && $parcala_cikanlar[0] == "" ) ){
                foreach( $parcala_cikanlar as $malzeme ){
                    $malz_parcala = explode("$", $malzeme );
                    $durum;
                    if( count($malz_parcala) == 0 ) continue;
                    if( $malz_parcala[0] == 2 ){
                        // araca önceden takılmamış barkodlu parçalar ( ÇIKAN )
                        if( $malz_parcala[5] == 'H' ) $durum = Parca_Tipi::$HURDA;
                        if( $malz_parcala[5] == 'R' ) $durum = Parca_Tipi::$REVIZE;
                        $cikanlar[] = array(
                            "parca_kontrol"         => $malz_parcala[0],
                            "tip"                   => $malz_parcala[1],
                            "parca_tipi"            => $malz_parcala[2],
                            "aciklama"              => $malz_parcala[3],
                            "miktar"                => $malz_parcala[4],
                            "durum"                 => $durum,
                            "revizyon_aciklamasi"   => "Revizyon talep"

                        );
                    } else {
                        // araca önceden takılmış barkodlu parçalar ve
                        // kısayolda olmayan barkodsuz parçalar
                        if( count($malz_parcala) == 0 ) continue;
                        if( $malz_parcala[1] == Parca_Tipi::$BARKODSUZ ){
                            if( $malz_parcala[5] == 'H' ) $durum = Parca_Tipi::$HURDA;
                            if( $malz_parcala[5] == 'R' ) $durum = Parca_Tipi::$REVIZE;
                            $cikanlar[] = array(
                                "parca_kontrol"         => $malz_parcala[0],
                                "stok_kodu"             => $malz_parcala[3],
                                "tip"                   => $malz_parcala[1],
                                "miktar"                => $malz_parcala[4],
                                "durum"                 => $durum
                            );
                        } else {
                            if( $malz_parcala[3] == 'H' ) $durum = Parca_Tipi::$HURDA;
                            if( $malz_parcala[3] == 'R' ) $durum = Parca_Tipi::$REVIZE;
                            $cikanlar[] = array(
                                "parca_kontrol"         => $malz_parcala[0],
                                "stok_kodu"             => $malz_parcala[2],
                                "tip"                   => $malz_parcala[1],
                                "durum"                 => $durum,
                                "revizyon_aciklamasi"   => "Revizyo talep"
                            );
                        }
                    }
                }
            }
            $DATA["personel"] = $personel_data;
            $DATA["cikanlar"] = $cikanlar;
            $DATA["girenler"] = $girenler;
            $Form = new Is_Emri_Formu();
            $Form->ekle( $form_detaylar_input, $personel_data, $girenler, $cikanlar );
            if( !$Form->is_ok() ){
                $OK = 0;
            }
            $TEXT = $Form->get_return_text();

            break;


    }

    $output = json_encode(array(
        "ok"    => $OK,
        "text"  => $TEXT,
        "data"  => $DATA,
        "oh"    => $_POST//json_decode($_POST["FORMDATA"], true)["personel_data"]
    ));
    echo $output;
    die;

}