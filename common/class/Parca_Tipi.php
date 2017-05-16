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

    public static   $AKTIF         = "AK",
                    $ARACTA        = "AR",
                    $REVIZYONDA    = "RE",
                    $HURDA         = "HU",
                    $KAYIP         = "KA",
                    $SATILDI       = "SA";

    public static   $ADET       = "Adet",
                    $LITRE      = "Litre",
                    $KG         = "Kilogram";

    public static   $VARYANTLI  = 1,
                    $VARYANTSIZ = 2;

    public function __construct( $id = null ){
        $db_keys = array( "id", "gid", "isim" );
        parent::__construct( DBT_PARCA_TIPLERI, $db_keys, $id );
    }

    public function otobus_onceki_girisler( $plaka, $varyant ){
        $output = array();
        $Otobus = new Otobus( $plaka );
        $varyant_str = "";
        $varyant_gid = "";

        if( $varyant != "" ){
            $Varyant = new Varyant( $varyant );
            if( $Varyant->get_details("parent") != null ){
                $varyant_gid = $Varyant->get_details("parent");
                $Parent = new Varyant( $varyant_gid );
                $varyant_str .= $Parent->get_details("isim") . " - ";
            }
            $varyant_str .= $Varyant->get_details("isim");
        }

        foreach( $Otobus->is_emri_formlarini_listele() as $form ){
            $Form = new Is_Emri_Formu( $form["gid"] );
            foreach( $Form->girenleri_listele() as $giren ){
                $Parca = new Parca($giren["stok_kodu"]);
                if( $Parca->get_details("parca_tipi") == $this->details["gid"] && $Parca->get_details("durum") == Parca_Tipi::$ARACTA ){
                    // parent varyantlari ayiriyoruz
                    if( $varyant_gid != "" && $Parca->get_details("varyant_gid") != $varyant_gid ) continue;
                    $output[] = array(
                        "stok_kodu" => $Parca->get_details("stok_kodu"),
                        "varyant"   => $varyant_str,
                        "aciklama"  => $Parca->get_details("aciklama"),
                        "km"        => $Form->get_details("gelis_km"),
                        "tarih"     => $Form->get_details("tarih")
                    );
                }
            }
        }
        return $output;
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

    public function varyantlari_listele( $tip ){
        $output = array();
        if( $tip == 1 ){
            // giris
            foreach( $this->pdo->query("SELECT * FROM " . DBT_VARYANT_TANIMLAMALAR . " WHERE parca_tipi = ?", array($this->details["gid"]))->results() as $v_tanimlama ){
                $Varyant = new Varyant( $v_tanimlama["varyant_gid"] );
                if( $Varyant->get_details("parent") == Data_Out::$BOS ){
                    $output[] = array( "isim" => $Varyant->get_details("isim"), "gid" => $v_tanimlama["varyant_gid"] );
                }
            }
        } else {
            // cikis
            foreach( $this->pdo->query("SELECT * FROM " . DBT_VARYANT_TANIMLAMALAR . " WHERE parca_tipi = ?", array($this->details["gid"]))->results() as $v_tanimlama ){
                $Varyant = new Varyant( $v_tanimlama["varyant_gid"] );
                if( $Varyant->get_details("parent") != Data_Out::$BOS ){
                    $Parent = new Varyant( $Varyant->get_details("parent") );
                    $output[] = array( "parent" => $Parent->get_details("isim"), "isim" => $Varyant->get_details("isim"), "gid" => $v_tanimlama["varyant_gid"] );
                }
            }

            // giriş varyanti var cikis varyanti yoksa; hem giriş hem çıkış varyantı tanımlanmış girişleri aliyoruz
            $girisler = $this->varyantlari_listele(1);
            if( count($girisler) == count($output)  ){
                $output = $girisler;
            }

        }
        return $output;
    }

    // iş emri formunda parça girişi yaparken, barkodsuz girişlerde varyant varsa, select value leri
    // direk parcalar tablosundan stok kodu yapiyoruz
    public function barkodsuz_varyantlari_parca_olarak_listele(){
        $output = array();
        foreach( $this->pdo->query("SELECT * FROM " . DBT_VARYANT_TANIMLAMALAR . " WHERE parca_tipi = ?", array($this->details["gid"]))->results() as $v_tanimlama ){
            $Varyant = new Varyant( $v_tanimlama["varyant_gid"]);
            foreach( $this->pdo->query("SELECT * FROM " . DBT_PARCALAR . " WHERE varyant_gid = ? && parca_tipi = ?", array( $v_tanimlama["varyant_gid"], $this->details["gid"]))->results() as $parca ) {
                $output[] = array( "isim" => $Varyant->get_details("isim"), "gid" => $parca["stok_kodu"] );
            }
        }
        return $output;
    }

    // stok.php mini dt data
    public function parca_tablo_data(){
        $data = array();
        foreach( $this->parcalari_listele(self::$AKTIF) as $parca ){
            $Varyant = new Varyant( $parca["varyant_gid"] );
            $parca_isim = $Varyant->get_details("isim");
            if( $this->details["tip"] == self::$BARKODSUZ ){
                $data[] = array(
                    "aciklama"  => $parca_isim,
                    "miktar"    => $parca["miktar"] . " " . $this->details["miktar_olcu_birimi"],
                    "stok_kodu" => $parca["stok_kodu"]
                );
            } else {
                $data[] = array(
                    "stok_kodu"     => $parca["stok_kodu"],
                    "varyant"       => $parca_isim,
                    "aciklama"      => $parca["aciklama"],
                    "fatura_no"     => $parca["fatura_no"],
                    "satici_firma"  => $parca["satici_firma"],
                    "revize"        => $parca["revize"]
                );
            }
        }
        return $data;
    }



    public function parcalari_listele( $tip ){
        return $this->pdo->query("SELECT * FROM " . DBT_PARCALAR . " WHERE parca_tipi = ? && durum = ?", array( $this->details["gid"], $tip ) )->results();
    }


    public function girisleri_listele(){

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

    // yapilmis parça girişinin detaylarini alirken kullandigimiz metod
    // parça giriş içeriklerini listelemez sadece miktar, tarih, gid
    public function get_girisler(){
        $output = array();
        $query = $this->pdo->query("SELECT * FROM " . DBT_PARCALAR . " WHERE parca_tipi = ?", array( $this->details["gid"] ) )->results();
        if( $this->details["tip"] == Parca_Tipi::$BARKODSUZ ){
            foreach( $query as $parca_varyant ){
                $giren_query = $this->pdo->query("SELECT * FROM " . DBT_BARKODSUZ_PARCA_GIRISLERI . " WHERE stok_kodu = ?", array( $parca_varyant["stok_kodu"] ) )->results();
                foreach( $giren_query as $giris ){
                    $Parca_Giris = new Parca_Girisi( $giris["parca_giris_gid"] );
                    if($Parca_Giris->exists()){
                        if( isset( $output[ $giris["parca_giris_gid"] ] ) ){
                            $output[ $giris["parca_giris_gid"] ]["miktar"]+= $giris["miktar"];
                        } else {
                            $Personel = new Personel( $Parca_Giris->get_details("giris_yapan") );
                            $output[ $giris["parca_giris_gid"] ] = array(
                                "miktar"        => $giris["miktar"],
                                "giris_yapan"   => $Personel->get_details("isim"),
                                "tarih"         => $Parca_Giris->get_details("tarih")
                            );
                        }

                    }
                }
            }
        } else{
            foreach( $query as $giris ){
                if( isset( $output[ $giris["parca_giris_gid"] ] ) ){
                    $output[ $giris["parca_giris_gid"] ]["miktar"]++;
                } else {
                    $Parca_Giris = new Parca_Girisi( $giris["parca_giris_gid"] );
                    if($Parca_Giris->exists()){
                        $Personel = new Personel( $Parca_Giris->get_details("giris_yapan") );
                        $output[ $giris["parca_giris_gid"] ] = array(
                            "miktar"        => 1,
                            "giris_yapan"   => $Personel->get_details("isim"),
                            "tarih"         => $Parca_Giris->get_details("tarih")
                        );
                    }
                }
            }
        }

        return Common::array_sort_by_column( $output, "tarih", SORT_DESC );
    }

    // burada cikislar otobuse girenler anlaminda
    public function get_cikislar(){
        $output = array();
        foreach( $this->pdo->query("SELECT * FROM " . DBT_ISEMRI_FORMU_GIRENLER )->results() as $cikis ){
            $Parca = new Parca( $cikis["stok_kodu"] );
            if( $Parca->get_details("parca_tipi") == $this->details["gid"] ){
                if( isset( $output[ $cikis["form_gid"] ] ) ){
                    $output[ $cikis["form_gid"] ]["miktar"]++;
                } else {
                    $Form = new Is_Emri_Formu( $cikis["form_gid"]  );
                    $output[ $cikis["form_gid"] ] = array(
                        "miktar"        => 1,
                        "plaka"         => $Form->get_details("plaka"),
                        "tarih"         => $Form->get_details("tarih"),
                        "surucu"        => $Form->get_details("surucu")
                    );
                }
            }
        }


        return Common::array_sort_by_column( $output, "tarih", SORT_DESC );
    }

    public function otobus_degisim_plan( $plaka ){
        if( $this->details["tip"] == Parca_Tipi::$BARKODSUZ ){
            return $this->barkodsuz_degisim_plan( $plaka );
        } else {
            return $this->barkodlu_degisim_plan( $plaka );
        }
    }

    private function barkodsuz_degisim_plan( $plaka ){
        $output = array();
        // barkodsuz parça tipinin her bir varyanti için formları kontrol edicez

        foreach( $this->get_cikislar() as $formgid => $parca ){
            $Form = new Is_Emri_Formu( $formgid );
            // parça tipi çıkışlarından girilen plaka olmayanlari dahil etme
            if( $Form->get_details("plaka") != $plaka ) continue;
            foreach( $Form->girenleri_listele() as $giren ){
                $Parca = new Parca( $giren["stok_kodu"] );
                $varyant_isim = Data_Out::$BOS;
                if( isset($giren["varyant_gid"] ) ){
                    $Varyant = new Varyant( $giren["varyant_gid"] );
                    $Ana_Varyant = new Varyant( $Parca->get_details("varyant_gid") );
                    $varyant_isim = $Ana_Varyant->get_details("isim") . " - " . $Varyant->get_details("isim");
                } else {
                    // giriş - çıkış varyanti olanlar icin parça tipinden varyant kontrolu yapiyoruz
                    $Parca_Tipi = new Parca_Tipi( $Parca->get_details("parca_tipi"));
                    if( $Parca_Tipi->get_details("varyantli") == 1 ){
                        $Ana_Varyant = new Varyant( $Parca->get_details("varyant_gid") );
                        $varyant_isim = $Ana_Varyant->get_details("isim");
                    }
                }

                if( $Parca->get_details("parca_tipi") == $this->details["gid"] ) {
                    $Surucu = new Personel($Form->get_details("surucu"));
                    $output[$varyant_isim][] = array(
                        "tarih"     => $Form->get_details("tarih"),
                        "km"        => $Form->get_details("gelis_km"),
                        "surucu"    => $Surucu->get_details("isim"),
                        "ekleme"    => $giren["ekleme"],
                        "miktar"    => $giren["miktar"] . " " . $this->details["miktar_olcu_birimi"]
                    );
                }
            }
        }

        $output["barkodsuz"] = true;
        return $output;
    }
    private function barkodlu_degisim_plan( $plaka ){
        $output = array();
        foreach( $this->get_cikislar() as $formgid => $parca ) {
            $Form = new Is_Emri_Formu($formgid);
            // parça tipi çıkışlarından girilen plaka olmayanlari dahil etme
            if ($Form->get_details("plaka") != $plaka) continue;
            foreach( $Form->girenleri_listele() as $giren ) {
                $Giren_Parca = new Parca($giren["stok_kodu"]);
                // barkodluda parça tipinden yakalıyoruz
                if( $Giren_Parca->get_details("parca_tipi") == $this->details["gid"] ){
                    $Surucu = new Personel(  $Form->get_details("surucu") );
                    $output[] = array(
                        "tarih"     => $Form->get_details("tarih"),
                        "km"        => $Form->get_details("gelis_km"),
                        "surucu"    => $Surucu->get_details("isim")
                    );
                }
            }
        }
        return $output;
    }

    private function bazli_istatistik( $baz ){
        $output = array();
        foreach( $this->get_cikislar() as $form_id => $form_data ){
            if( isset( $output[$form_data[$baz]] ) ){
                $output[$form_data[$baz]]++;
            } else {
                $output[$form_data[$baz]] = 1;
            }
        }
        return $output;
    }

    public function otobus_istatistik(){
        return $this->bazli_istatistik("plaka");
    }

    public function surucu_istatistik(){
        return $this->bazli_istatistik("surucu");
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