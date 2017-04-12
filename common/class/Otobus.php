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
        $detay_array = array(
            array(
                array(
                    "label" => "Plaka",
                    "value" => $this->details["plaka"]
                ),
                array(
                    "label" => "Ruhsat Kapı Kodu",
                    "value" => $this->details["ruhsat_kapi_kodu"]
                ),
                array(
                    "label" => "Aktif Kapı Kodu",
                    "value" => $this->details["aktif_kapi_kodu"]
                )
            ),
            array(
                array(
                    "label" => "Marka / Model ( Model Yılı )",
                    "value" => $this->details["marka"] . " " . $this->details["model"] . " ( " . $this->details["model_yili"] . " )"
                )
            ),
            array(
                array(
                    "label" => "Sahip",
                    "value" => $this->details["sahip"]
                )
            ),
            array(
                array(
                    "label" => "OGS",
                    "value" => $this->details["ogs"]
                )
            )
        );
        return Popup_Info::init( $detay_array );
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


}