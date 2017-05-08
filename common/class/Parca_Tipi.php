<?php

/**
 * Created by PhpStorm.
 * User: Jeppe
 * Date: 05.05.2017
 * Time: 12:48
 */
class Parca_Tipi extends Data_Out {

    public static   $MEKANIK    = 1,
                    $ELEKTRONIK = 2,
                    $SARF       = 3,
                    $IC_TRIM    = 4,
                    $DIS_TRIM   = 5;

    public static   $BARKODLU   = 1,
                    $BARKODSUZ  = 2;

    public static   $AKTIF         = 1,
                    $ARACTA        = 2,
                    $REVIZYONDA    = 3,
                    $HURDA         = 4,
                    $KAYIP         = 5,
                    $SATILDI       = 6;

    public static   $ADET       = "Adet",
                    $LITRE      = "Litre",
                    $KG         = "Kilogram";

    public static   $VARYANTLI  = 1,
                    $VARYANTSIZ = 2;

    public function __construct( $id = null ){
        $db_keys = array( "id", "gid", "isim" );
        parent::__construct( DBT_PARCA_TIPLERI, $db_keys, $id );
    }

    public function ekle( $input ){
        $Kontrol_Parca_Tipi = new Parca_Tipi( $input["isim"] );
        if( $Kontrol_Parca_Tipi->exists() ){
            $this->return_text = "Bu parça tipi zaten eklenmiş.";
            $this->ok = false;
            return;
        }
        $this->details["gid"]  = Gitas_Hash::hash_olustur( Gitas_Hash::$PARCA_TIPI, array( "isim" => $input["isim"] ) );
        $insert = array(
            "gid"                                   => $this->details["gid"],
            "tip"                                   => $input["tip"],
            "isim"                                  => $input["isim"],
            "kategori"                              => $input["kategori"],
            "miktar_olcu_birimi"                    => $input["miktar_olcu_birimi"],
            "ideal_degisim_sikligi_alt"             => $input["ideal_degisim_sikligi_alt"],
            "ideal_degisim_sikligi_ust"             => $input["ideal_degisim_sikligi_ust"],
            "ideal_degisim_sikligi_tarih_alt"       => $input["ideal_degisim_sikligi_tarih_alt"],
            "ideal_degisim_sikligi_tarih_ust"       => $input["ideal_degisim_sikligi_tarih_ust"],
            "kritik_seviye_limiti"                  => $input["kritik_seviye_limiti"]
        );
        $tanimlanan_varyantlar = array();
        if( isset($input["varyantlar"]) ){
            foreach( $input["varyantlar"] as $varyant ){
                $Varyant = new Varyant( $varyant );
                $Varyant->parca_tipine_tanimla( $this->details["gid"] );
                $tanimlanan_varyantlar[] = $varyant;
            }
            $insert["varyantli"] = 1;
        }
        if( $this->pdo->insert($this->table, $insert) ){
            $this->details["isim"] = $input["isim"];
            $this->return_text = "Parça tipi eklendi.";
        } else {
            $this->ok = false;
            $this->return_text = "Parça tipi eklenirken bir hata oluştu.";
            foreach( $tanimlanan_varyantlar as $varyant ){
                // eklenen varyantlari sil hata durumunda
                $Varyant = new Varyant( $varyant );
                $Varyant->tanimlamayi_kaldir( $this->details["gid"] );
            }
        }
    }

    public function varantli_yap(){
        $this->pdo->query("UPDATE " . $this->table . " SET varyantli = ? WHERE gid = ?", array( 1, $this->details["gid"] ) );
    }

    public function varyantlari_listele( $tip = 1 ){
        $output = array();
        if( $tip == 1 ){
            // giris
            foreach( $this->pdo->query("SELECT * FROM " . DBT_VARYANT_TANIMLAMALAR . " WHERE parca_tipi = ?", array($this->details["gid"]))->results() as $v_tanimlama ){
                $Varyant = new Varyant( $v_tanimlama["varyant_gid"] );
                if( $Varyant->get_details("parent") == null ){
                    $output[] = array( "isim" => $Varyant->get_details("isim"), "gid" => $v_tanimlama["varyant_gid"] );
                }
            }
        } else {
            // cikis


        }
        return $output;
    }

    // stok.php dt data
    public function parca_tablo_data(){

    }

    public function girisleri_listele(){

    }

    public function cikislari_listele(){

    }

    public function otobus_degisim_plan( $plaka ){

    }

    public function surucu_degisim_plan( $sicil_no ){

    }

    public function servisci_degisim_plan( $personel_gid ){

    }

    public function duzenle( $input ){
        $this->pdo->query("UPDATE " . $this->table . " SET kategori = ?, miktar_olcu_birimi = ?, ideal_degisim_sikligi_tarih_alt = ?, ideal_degisim_sikligi_tarih_ust = ?, ideal_degisim_sikligi_alt = ?, ideal_degisim_sikligi_ust = ?, kritik_seviye_limiti = ? WHERE gid = ?", array(
            $input["kategori"],
            $input["miktar_olcu_birimi"],
            $input["ideal_degisim_sikligi_tarih_alt"],
            $input["ideal_degisim_sikligi_tarih_ust"],
            $input["ideal_degisim_sikligi_alt"],
            $input["ideal_degisim_sikligi_ust"],
            $input["kritik_seviye_limiti"],
            $this->details["gid"]
        ));
        $this->return_text = "Parça tipi düzenlendi.";
    }

    public function sil( $input ){
        $this->pdo->query("DELETE FROM " . $this->table . " WHERE gid = ?", array( $this->details["gid"] ) );
        $this->return_text = "Parça tipi silindi.";
    }

    public static function kategori_convert( $kat ){
        if( $kat == self::$MEKANIK ) return "Mekanik";
        if( $kat == self::$ELEKTRONIK ) return "Elektronik";
        if( $kat == self::$SARF ) return "Sarf";
        if( $kat == self::$IC_TRIM ) return "İç Trim";
        if( $kat == self::$DIS_TRIM ) return "Dış Trim";
        return "Veri Yok";
    }

    public function get_duzenle_form(){
        $kategori_options = array( "Mekanik", "Elektronik", "Sarf", "İç Trim", "Dış Trim");
        $miktar_options = array( "Adet", "Litre", "Kilogram");
        $form_array = array(
            "id" => "patip_duzenle",
            "action" => "",
            "method" => "post",
            "rows" => array(
                array(
                    array(
                        "type" => Popup_Form::$SELECT,
                        "key" => "Kategori",
                        "name" => "kategori",
                        "data" => Common::array_select_html( array( "key" => "kategori", "req" => true, "selected" => $this->details["kategori"], "array" => $kategori_options, "form_prefix" => "patip_duzenle" ) )
                    )
                ),
                array(
                    array(
                        "type" => Popup_Form::$SELECT,
                        "key" => "Miktar Ölçü Birimi",
                        "name" => "miktar_olcu_birimi",
                        "data" => Common::array_select_html( array( "key" => "miktar_olcu_birimi", "hepsival" => true, "req" => true, "selected" => $this->details["miktar_olcu_birimi"], "array" => $miktar_options, "form_prefix" => "patip_duzenle" ) )
                    )
                ),
                array(
                    array(
                        "type" => Popup_Form::$TEXT,
                        "key" => "İDS KM Alt",
                        "name" => "ideal_degisim_sikligi_alt",
                        "class" => array( Popup_Form::$CLS_POSNUM, Popup_Form::$CLS_KISA ),
                        "value" => $this->details["ideal_degisim_sikligi_alt"]
                    ),
                    array(
                        "type" => Popup_Form::$TEXT,
                        "key" => "İDS KM Üst",
                        "name" => "ideal_degisim_sikligi_ust",
                        "class" => array( Popup_Form::$CLS_POSNUM, Popup_Form::$CLS_KISA ),
                        "value" => $this->details["ideal_degisim_sikligi_ust"]
                    )
                ),
                array(

                    array(
                        "type" => Popup_Form::$TEXT,
                        "key" => "İDS Ay Alt",
                        "name" => "ideal_degisim_sikligi_tarih_alt",
                        "class" => array( Popup_Form::$CLS_POSNUM, Popup_Form::$CLS_KISA ),
                        "value" => $this->details["ideal_degisim_sikligi_tarih_alt"]
                    ),
                    array(
                        "type" => Popup_Form::$TEXT,
                        "key" => "İDS Ay Üst",
                        "name" => "ideal_degisim_sikligi_tarih_ust",
                        "class" => array( Popup_Form::$CLS_POSNUM, Popup_Form::$CLS_KISA ),
                        "value" => $this->details["ideal_degisim_sikligi_tarih_ust"]
                    )

                ),
                array(
                    array(
                        "type" => Popup_Form::$TEXT,
                        "key" => "Kritik Seviye Limiti",
                        "name" => "kritik_seviye_limiti",
                        "class" => array( Popup_Form::$CLS_POSNUM, Popup_Form::$CLS_KISA ),
                        "value" => $this->details["kritik_seviye_limiti"]
                    ),
                    array(
                        "type" => Popup_Form::$HIDDEN,
                        "name" => "req",
                        "value" => "parca_tipi_duzenle"
                    ),
                    array(
                        "type" => Popup_Form::$HIDDEN,
                        "name" => "item_id",
                        "value" => $this->details["gid"]
                    )
                )
            )
        );
        return Popup_Form::init( $form_array );

    }

}