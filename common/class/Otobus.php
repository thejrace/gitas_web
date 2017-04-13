<?php

/**
 * Created by PhpStorm.
 * User: Jeppe
 * Date: 21.03.2017
 * Time: 13:06
 */
class Otobus extends Data_Out{

    public static   $AKTIF = 1,
                    $SERVIS = 2;

    public function __construct( $id = null ){
        $db_keys = array( "id", "ruhsat_kapi_kodu", "aktif_kapi_kodu", "plaka" );
        parent::__construct( DBT_OTOBUSLER, $db_keys, $id );
    }

    public function plaka_kontrol( $plaka ){
        $kontrol = $this->pdo->query("SELECT * FROM " . $this->table . " WHERE plaka = ?",array( $plaka ) )->results();
        if( count($kontrol) != 0 ){
            $this->ok = false;
            $this->return_text = "Bu plakalı araç zaten kayıtlı.";
            return false;
        }
        return true;
    }

    public function duzenleme_plaka_kontrol( $plaka ){
        $kontrol = $this->pdo->query("SELECT * FROM " . $this->table . " WHERE plaka = ? && id != ?",array( $plaka, $this->details["id"] ) )->results();
        if( count($kontrol) != 0 ){
            $this->ok = false;
            $this->return_text = "Bu plakalı araç zaten kayıtlı.";
            return false;
        }
        return true;
    }


    public function ekle( $input ){

        if( !$this->plaka_kontrol( $input["plaka"] ) ) return false;

        $ekle = $this->pdo->insert($this->table, array(
            "plaka"             => $input["plaka"],
            "ruhsat_kapi_kodu"  => $input["ruhsat_kapi_kodu"],
            "aktif_kapi_kodu"   => $input["ruhsat_kapi_kodu"],
            "marka"             => $input["marka"],
            "model"             => $input["model"],
            "model_yili"        => $input["model_yili"],
            "sahip"             => $input["sahip"],
            "ogs"               => $input["ogs"],
            "durum"             => self::$AKTIF
        ));
        if( !$ekle ){
            $this->return_text = "Otobüs eklenirken bir hata oluştu.";
            $this->ok = false;
            return false;
        }
        $this->details["id"] = $this->pdo->lastInsertedId();
        $this->return_text = "Otobüs eklendi.";
        return true;
    }

    public function duzenle( $input ){
        $guncelle = $this->pdo->query("UPDATE " . $this->table . " SET 
            aktif_kapi_kodu = ?,
            marka = ?,
            model = ?,
            model_yili = ?,
            ogs = ? WHERE plaka = ?",
            array(
                   $input["aktif_kapi_kodu"],
                   $input["marka"],
                   $input["model"],
                   $input["model_yili"],
                   $input["ogs"],
                   $this->details["plaka"]
            )
        );
        if( !$guncelle ){
            $this->return_text = "Otobüs güncellenirken bir hata oluştu.";
            $this->ok = false;
            return false;
        }
        $this->return_text = "Otobüs güncellendi.";
    }

    public function durum_guncelle( $yeni_durum ){
        $guncelle = $this->pdo->query("UPDATE " . $this->table . " SET durum = ? WHERE plaka = ?", array( $yeni_durum, $this->details["plaka"] ) );
        if( !$guncelle ){
            $this->return_text = "Otobüs durumu güncellenirken bir hata oluştu.";
            $this->ok = false;
            return false;
        }
        $this->return_text = "Otobüs güncellendi.";
    }

    public function sil(){

    }

    public function parcalari_listele( $gc, $parca_tipi ){
        $output = array();
        $formlar = $this->is_emri_formlarini_listele();
        $Parca_Tipi = new Parca_Tipi( $parca_tipi );

        foreach( $formlar as $form ) {
            $Form = new Is_Emri_Formu($form["gid"]);
            if( $gc == 1 ){
                $liste = $Form->form_girenleri_listele();
            } else {
                $liste = $Form->form_cikanlari_listele();
            }
            foreach ($liste as $parca) {
                $Parca = new Barkodlu_Parca( $parca["stok_kodu"] );
                if( $Parca->exists() ){
                    if( $Parca->get_details("tip") == $Parca_Tipi->get_details("gid") ){
                        $output[] = array(
                            "stok_kodu" => $Parca->get_details("stok_kodu"),
                            "aciklama"  => $Parca->get_details("aciklama"),
                            "km"        => $Form->get_details("gelis_km"),
                            "tarih"     => $Form->get_details("tarih")
                        );
                    }
                }

            }
        }
        return $output;
    }

    public function is_emri_formlarini_listele(){
        return $this->pdo->query("SELECT * FROM " . DBT_ISEMRI_FORMLARI . " WHERE plaka = ?", array( $this->details["plaka"]))->results();
    }

    public function servis_uyarilarini_kontrol_et(){


    }

    public function detay_html(){
        $statdata = array(
            array(
                "header" => "OTOBÜS DETAYLARI",
                "items"  => array(
                    array( "key" => "PLAKA", "val" => $this->details["plaka"]  ),
                    array( "key" => "RUHSAT KAPI KODU", "val" => $this->details["ruhsat_kapi_kodu"] ),
                    array( "key" => "AKTİF KAPI KODU", "val" => $this->details["aktif_kapi_kodu"] ),
                    array( "key" => "MARKA / MODEL ( MODEL YILI )", "val" => $this->details["marka"] . " " . $this->details["model"] . " ( " . $this->details["model_yili"] . " )" ),
                    array( "key" => "SAHİP", "val" => $this->details["sahip"] ),
                    array( "key" => "OGS", "val" => $this->details["ogs"] )
                )
            )
        );
        return Popup_Stats::init( $statdata );
    }

    public function ayarlar_html(){
        $form_array = array(
            "id" => "otobus_ayarlar",
            "action" => "",
            "method" => "post",
            "rows" => array(
                array(
                    array(
                        "type" => Popup_Form::$TEXT,
                        "key" => "Aktif Kapı Kodu",
                        "name" => "aktif_kapi_kodu",
                        "class" => Popup_Form::$CLS_REQ,
                        "value" => $this->details["aktif_kapi_kodu"]
                    )
                ),
                array(
                    array(
                        "type" => Popup_Form::$TEXT,
                        "key"  => "OGS",
                        "name" => "ogs",
                        "class" => Popup_Form::$CLS_REQ,
                        "value" => $this->details["ogs"]
                    )
                ),
                array(
                    array(
                        "type" => Popup_Form::$TEXT,
                        "key" => "Model Yılı",
                        "name" => "model_yili",
                        "class" => array( Popup_Form::$CLS_REQ, Popup_Form::$CLS_POSNUM ),
                        "value" => $this->details["model_yili"]
                    )
                ),
                array(
                    array(
                        "type"  => Popup_Form::$SELECT,
                        "key" => "Marka",
                        "data"  => Common::db_select_html( array( "key" => "marka", "opt_val_key" => "isim", "opt_text_key" => "isim", "table" => DBT_OTOBUS_MARKALAR, "req" => true, "form_prefix" => "otobus", "selected" => $this->details["marka"] ) ),
                        "name"  => "marka"
                    ),
                    array(
                        "type"  => Popup_Form::$SELECT,
                        "key" => "Model",
                        "data"  => Common::db_select_html( array( "key" => "model", "opt_val_key" => "isim", "opt_text_key" => "isim", "table" => DBT_OTOBUS_MODELLER, "req" => true, "form_prefix" => "otobus", "selected" => $this->details["model"] ) ),
                        "name"  => "model"
                    ),
                    array(
                        "type" => Popup_Form::$HIDDEN,
                        "name" => "req",
                        "value" => "ayarlar_form"
                    ),
                    array(
                        "type" => Popup_Form::$HIDDEN,
                        "name" => "item_id",
                        "value" => $this->details["plaka"]
                    )
                )
            )
        );
        return Popup_Form::init( $form_array );
    }

    /**
        Servis İstatistikleri
     *      - İş emri formları
     *      - En çok değişen parça tipi
     *      - En çok servise gelen sürücü
     *
     *  Sefer İstatistikleri
     *      - Toplam sefer
     *      - Tamam seferler
     *      - Zayi seferler
     *      - Favori kapı kodu
     *      - Favori sürücü
     *      - Gitaş KM
     *      - IETT KM
     *      - Toplam süre

     */
    public function stats_init(){
        $formlar = $this->is_emri_formlarini_listele();
        $this->details["stats"]["is_emri_formlari"] = count( $formlar );
        $girenler_temp = array();
        $suruculer_temp = array();
        foreach( $formlar as $form ){
            $Form = new Is_Emri_Formu( $form["gid"] );
            foreach( $Form->form_girenleri_listele(true) as $giren ){

                if( isset($girenler_temp[$giren["stok_kodu"]] ) ){
                    $girenler_temp[$giren["stok_kodu"]]++;
                } else {
                    $girenler_temp[$giren["stok_kodu"]] = 1;
                }
            }
            if( isset($suruculer_temp[$Form->get_details("surucu")]) ){
                $suruculer_temp[$Form->get_details("surucu")]++;
            } else {
                $suruculer_temp[$Form->get_details("surucu")] = 1;
            }
        }
        $temp_count = 0;
        foreach( $suruculer_temp as $key => $count ){
            if( $count > $temp_count ) {
                $temp_count = $count;
                $temp_item = $key;
            }
        }
        if( $temp_count > 0 ){
            $Surucu = new Personel($temp_item);
            $this->details["stats"]["en_cok_servise_gelen_surucu"] = $Surucu->get_details("isim") . " ( " . $temp_count . " ) ";
            $this->details["stats"]["en_cok_servise_gelen_surucu_gid"] = $temp_item;
        } else {
            $this->details["stats"]["en_cok_servise_gelen_surucu"] = Popup_Stats::$VERI_YOK;
            $this->details["stats"]["en_cok_servise_gelen_surucu_gid"] = Popup_Stats::$VERI_YOK;
        }
        $temp_count = 0;
        foreach( $girenler_temp as $key => $count ){
            if( $count > $temp_count ) {
                $temp_count = $count;
                $temp_item = $key;
            }
        }
        if( $temp_count > 0 ){
            $Parca = Parca::get( $temp_item );
            $Parca_Tipi = new Parca_Tipi( $Parca->get_details("tip") );
            $this->details["stats"]["en_cok_degisen_parca_tipi"] = $Parca_Tipi->get_details("isim") . " - "  . $Parca->get_details("aciklama") . " ( " . $temp_count . " ) ";
            $this->details["stats"]["en_cok_degisen_parca_tipi_gid"] = $temp_item;
        } else {
            $this->details["stats"]["en_cok_degisen_parca_tipi"] = Popup_Stats::$VERI_YOK;
            $this->details["stats"]["en_cok_degisen_parca_tipi_gid"] = Popup_Stats::$VERI_YOK;
        }
    }

    public function stats_html(){

        $statdata = array(
            array(
                "header" => "SERVİS İSTATİSTİKLERİ",
                "items"  => array(
                    array( "key" => "İŞ EMRİ FORMLARI", "val" => $this->details["stats"]["is_emri_formlari"], "href" => URL_ISEMRI_FORMLARI . "?filter_plaka=" . $this->details["plaka"] ),
                    array( "key" => "EN ÇOK DEĞİŞEN PARÇA", "val" => $this->details["stats"]["en_cok_degisen_parca_tipi"] ),
                    array( "key" => "EN ÇOK SERVİSE GELEN SÜRÜCÜ", "val" => $this->details["stats"]["en_cok_servise_gelen_surucu"], "href" => URL_ISEMRI_FORMLARI . "?filter_plaka=" . $this->details["plaka"] . "&filter_surucu=" . $this->details["stats"]["en_cok_servise_gelen_surucu_gid"]  )
                )
            )
        );
        return Popup_Stats::init( $statdata );

    }


}