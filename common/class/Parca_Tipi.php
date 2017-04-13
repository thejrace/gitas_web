<?php

/**
 * Created by PhpStorm.
 * User: Jeppe
 * Date: 06.03.2017
 * Time: 11:32
 */
class Parca_Tipi extends Data_Out {

    public static   $MEKANIK    = 1,
                    $ELEKTRONIK = 2,
                    $SARF       = 3,
                    $IC_TRIM    = 4,
                    $DIS_TRIM   = 5;

    public static   $BARKODLU   = 1,
                    $BARKODSUZ  = 2;


    public static   $REVIZE         = 1,
                    $HURDA          = 2,
                    $CIKMADI_STOK   = 3, // stokta olan parca cikmadi
                    $CIKMADI        = 4;

    public static   $ADET       = "Adet",
                    $LITRE      = "Litre",
                    $KG         = "Kilogram";


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

        $gid = Gitas_Hash::hash_olustur( Gitas_Hash::$PARCA_TIPI, array( "isim" => $input["isim"] ) );

        if( $this->pdo->insert( $this->table, array(
            "gid"                                   => $gid,
            "isim"                                  => $input["isim"],
            "tip"                                   => $input["tip"],
            "kategori"                              => $input["kategori"],
            "miktar_olcu_birimi"                    => $input["miktar_olcu_birimi"],
            "ideal_degisim_sikligi_alt"             => $input["ideal_degisim_sikligi_alt"],
            "ideal_degisim_sikligi_ust"             => $input["ideal_degisim_sikligi_ust"],
            "ideal_degisim_sikligi_tarih_alt"       => $input["ideal_degisim_sikligi_tarih_alt"],
            "ideal_degisim_sikligi_tarih_ust"       => $input["ideal_degisim_sikligi_tarih_ust"],
            "kritik_seviye_limiti"                  => $input["kritik_seviye_limiti"]
        )) ){
            $this->details["gid"] = $gid;
            $this->details["isim"] = $input["isim"];
            $this->return_text = "Parça tipi eklendi.";
        } else {
            $this->ok = false;
            $this->return_text = "Parça tipi eklenirken bir hata oluştu.";
        }
    }

    public function varyantlari_listele(){
        $varyantlar = array();
        if( $this->details["tip"] == self::$BARKODSUZ ){
            $query = $this->pdo->query("SELECT * FROM " . DBT_BARKODSUZ_PARCALAR . " WHERE tip = ?", array( $this->details["gid"]) )->results();
            foreach( $query as $data ){
                $varyantlar[] = array( "stok_kodu" => $data["stok_kodu"], "aciklama" => $data["aciklama"] );
            }
        }
        return $varyantlar;
    }

    public function parca_tablo_data(){
        $data = array();
        if( $this->details["tip"] == self::$BARKODSUZ ){
            $query = $this->pdo->query("SELECT * FROM " . DBT_BARKODSUZ_PARCALAR . " WHERE tip = ?", array( $this->details["gid"]) )->results();
            foreach( $query as $parca ) {
                if( $parca["gcmod"] == 0 ) continue;
                $data[] = array(
                    "aciklama"  => $parca["aciklama"],
                    "miktar"    => $parca["miktar"] . " " . $this->details["miktar_olcu_birimi"],
                    "stok_kodu" => $parca["stok_kodu"]
                );
            }
        } else {
            $query = $this->pdo->query("SELECT * FROM " . DBT_BARKODLU_PARCALAR . " WHERE tip = ? && durum = ? && kullanildi = ? && kayip = ?", array( $this->details["gid"], 1, 0, 0) )->results();
            foreach( $query as $parca ){
                $data[] = array(
                    "stok_kodu"     => $parca["stok_kodu"],
                    "aciklama"      => $parca["aciklama"],
                    "fatura_no"     => $parca["fatura_no"],
                    "satici_firma"  => $parca["satici_firma"],
                    "revize"        => $parca["revize"]
                );
            }
        }
        return $data;
    }

    // yapilmis parça girişinin detaylarini alirken kullandigimiz metod
    // parça giriş içeriklerini listelemez sadece miktar, tarih, gid
    public function get_girisler(){
        $output = array();
        if( $this->details["tip"] == Parca_Tipi::$BARKODSUZ ){
            foreach( $this->varyantlari_listele() as $varyant ){
                $query = $this->pdo->query("SELECT * FROM " . DBT_BARKODSUZ_PARCA_GIRISLERI . " WHERE stok_kodu = ?", array( $varyant["stok_kodu"] ) )->results();
                $Parca = new Barkodsuz_Parca( $varyant["stok_kodu"] );
                foreach( $query as $giris ){
                    if( $Parca->get_details("tip") == $this->details["gid"] ){
                        if( isset( $output[ $giris["parca_giris_gid"] ] ) ){
                            $output[ $giris["parca_giris_gid"] ]["miktar"]++;
                        } else {
                            $Parca_Giris = new Parca_Girisi( $giris["parca_giris_gid"] );
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
            $query = $this->pdo->query("SELECT * FROM " . DBT_BARKODLU_PARCALAR . " WHERE tip = ?", array( $this->details["gid"] ) )->results();
            foreach( $query as $giris ){
                if( isset( $output[ $giris["parca_giris_id"] ] ) ){
                    $output[ $giris["parca_giris_id"] ]["miktar"]++;
                } else {
                    $Parca_Giris = new Parca_Girisi( $giris["parca_giris_id"] );
                    $Personel = new Personel( $Parca_Giris->get_details("giris_yapan") );
                    $output[ $giris["parca_giris_id"] ] = array(
                        "miktar"        => 1,
                        "giris_yapan"   => $Personel->get_details("isim"),
                        "tarih"         => $Parca_Giris->get_details("tarih")
                    );
                }
            }
        }

        return Common::array_sort_by_column( $output, "tarih", SORT_DESC );
    }

    // burada cikislar otobuse girenler anlaminda
    public function get_cikislar(){
        $output = array();
        if( $this->details["tip"] == Parca_Tipi::$BARKODSUZ ){
            foreach( $this->varyantlari_listele() as $varyant ){
                $Parca = new Barkodsuz_Parca( $varyant["stok_kodu"] );
                $query = $this->pdo->query("SELECT * FROM " . DBT_ISEMRI_FORMU_GIRENLER . " WHERE stok_kodu = ?", array( $varyant["stok_kodu"] ) )->results();
                foreach( $query as $cikis ){
                    if( $Parca->get_details("tip") == $this->details["gid"] ){
                        if( isset( $output[ $cikis["form_gid"] ] ) ){
                            $output[ $cikis["form_gid"] ]["miktar"]++;
                        } else {
                            $Form = new Is_Emri_Formu( $cikis["form_gid"]  );
                            $output[ $cikis["form_gid"] ] = array(
                                "miktar"        => $cikis["miktar"],
                                "plaka"         => $Form->get_details("plaka"),
                                "tarih"         => $Form->get_details("tarih"),
                                "surucu"        => $Form->get_details("surucu")
                            );
                        }
                    }
                }
            }
        } else {
            // barkodsuz parçalarda varyant olmadigi icin, barkodlu parçalar tablosundan parça tipinin
            // kullanildi = 1 VEYA revize = 1 kosuluna uyani aliyoruz
            // revize = 1 kosulunun sebebi; parca revize olduktan sonra kullanildi = 0 olacak iş emri formunda listelenebilmesi için.
            // o yuzden revize = 1 ise demek ki parça kullanılmış, bu sebepten çıkışlar listelemesi yaparken dikkate aliyoruz
            $query = $this->pdo->query("SELECT * FROM " . DBT_BARKODLU_PARCALAR . " WHERE tip = ? && ( kullanildi = ? || revize = ? )", array( $this->details["gid"], 1, 1 ) )->results();
            foreach( $query as $parca ){
                $query_cikis = $this->pdo->query("SELECT * FROM " . DBT_ISEMRI_FORMU_GIRENLER . " WHERE stok_kodu = ?", array( $parca["stok_kodu"] ) )->results();
                if( count($query_cikis) > 0 ){
                    if( isset( $output[ $query_cikis[0]["form_gid"] ] ) ){
                        $output[ $query_cikis[0]["form_gid"] ]["miktar"]++;
                    } else {
                        $Form = new Is_Emri_Formu( $query_cikis[0]["form_gid"]  );
                        $output[ $query_cikis[0]["form_gid"] ] = array(
                            "miktar"        => 1,
                            "plaka"         => $Form->get_details("plaka"),
                            "tarih"         => $Form->get_details("tarih"),
                            "surucu"        => $Form->get_details("surucu")
                        );
                    }
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
        foreach( $this->varyantlari_listele() as $varyant ){
            foreach( $this->get_cikislar() as $formgid => $parca ){
                $Form = new Is_Emri_Formu( $formgid );
                // parça tipi çıkışlarından girilen plaka olmayanlari dahil etme
                if( $Form->get_details("plaka") != $plaka ) continue;
                foreach( $Form->form_girenleri_listele(true) as $giren ){
                    // eger çıkan parça barkodluysa siradaki parçaya geç
                    if( $giren["tip"] == Parca_Tipi::$BARKODLU ) continue;
                    $Giren_Parca = Parca::get($giren["stok_kodu"]);
                    // eger cikan parcayla varyantin stok kodu ayniysa işlem yapiyoruz
                    if( $Giren_Parca->get_details("stok_kodu") == $varyant["stok_kodu"] ){
                        $output[$varyant["aciklama"]][] = array(
                            "tarih" => $Form->get_details("tarih"),
                            "km"    => $Form->get_details("gelis_km")
                        );
                    }
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
            foreach( $Form->form_girenleri_listele(true) as $giren ) {
                if ($giren["tip"] == Parca_Tipi::$BARKODSUZ) continue;
                $Giren_Parca = Parca::get($giren["stok_kodu"]);
                // barkodluda parça tipinden yakalıyoruz
                if( $Giren_Parca->get_details("tip") == $this->details["gid"] ){
                    $output[] = array(
                        "tarih" => $Form->get_details("tarih"),
                        "km"    => $Form->get_details("gelis_km")
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