<?php

/**
 * Created by PhpStorm.
 * User: Jeppe
 * Date: 05.05.2017
 * Time: 12:48
 */
class Is_Emri_Formu extends Data_Out{

    /** Eger form kaydedilirken herhangi bir hata olursa;
     *    # Barkodlu parcalarin hurda, revize, kullanildi verileri eski haline dondurulecek.
     *    # Barkodsuz parcalarin ise stok miktari eski haline dondurulecek.
     * Bu array de tutucaz parcalarin eski hallerini
     */
    private $stok_parcalar_eski_kayit = array();
    /** Stokta olmayan parçayi ekliyoruz, eger hata olursa stoktan silicez onu,
     * eklenen parcalarin stok kodlarini bu array de tutucaz. **/
    private $stokta_olmayan_parca_stok_kodlari = array();
    public function __construct( $id = null ){
        $db_keys = array( "id", "gid" );
        parent::__construct( DBT_ISEMRI_FORMLARI, $db_keys, $id );
    }
    public function get_eski_data_test(){
        return $this->stok_parcalar_eski_kayit;
    }
    public function get_stokta_olmayan_data_test(){
        return $this->stokta_olmayan_parca_stok_kodlari;
    }
    /** plakayi girdigi anda ajax ile bu metodla gid olusturup, forma alicaz gidi
    /** parca - revizyon talep yaparken form id yi tutabilmek için */
    public function gid_olustur( $plaka ){
        return Gitas_Hash::hash_olustur(Gitas_Hash::$IS_EMRI_FORMU, array( "plaka" => $plaka ) );
    }
    // input_girenler -> araca girenler
    // input_cikanlar -> aractan cikanlar
    public function ekle( $input_form_detaylari, $input_personeL_detay, $input_girenler, $input_cikanlar ){
        $this->details["gid"] = Gitas_Hash::hash_olustur(Gitas_Hash::$IS_EMRI_FORMU, array("plaka" => $input_form_detaylari["plaka"]));
        //$this->details["gid"] = $input_form_detaylari["form_gid"];
        if (!$this->pdo->insert($this->table, array(
            "gid"                   => $this->details["gid"],
            "plaka"                 => $input_form_detaylari["plaka"],
            "aktif_kapi_no"         => $input_form_detaylari["aktif_kapi_no"],
            "gelis_km"              => $input_form_detaylari["gelis_km"],
            "surucu"                => $input_form_detaylari["surucu"],
            "gelis_tarih"           => $input_form_detaylari["gelis_tarih"],
            "cikis_tarih"           => $input_form_detaylari["cikis_tarih"],
            "sikayet"               => $input_form_detaylari["sikayet"],
            "ariza_tespit"          => $input_form_detaylari["ariza_tespit"],
            "yapilan_onarim"        => $input_form_detaylari["yapilan_onarim"],
            "kalibrasyon_yapildi"   => $input_form_detaylari["kalibrasyon_yapildi"],
            "arac_yikama"           => $input_form_detaylari["arac_yikama"],
            "durum"                 => $input_form_detaylari["durum"],
            "giris_yapan"           => Active_User::get_details("id"),
            "tarih"                 => Common::get_current_datetime()
        ))
        ) {
            $this->return_text = "Form eklenirken bir hata oluştu.";
            $this->ok = false;
            return;
        }
        foreach ($input_personeL_detay as $personel) {
            if (!$this->pdo->insert(DBT_ISEMRI_FORMU_PERSONEL_DETAY, array(
                "form_gid"  => $this->details["gid"],
                "personel"  => $personel["personel"],
                "is_tanimi" => $personel["is_tanimi"],
                "baslama"   => $personel["baslama"],
                "bitis"     => $personel["bitis"]
            ))
            ) {
                $this->return_text = "Personel detay eklenirken hata oluştu.";
                $this->ok = false;
                return;
            }
        }

        // araca girenler
        foreach( $input_girenler as $giren ){
            $Parca = new Parca($giren["stok_kodu"]);
            if( $giren["tip"] == Parca_Tipi::$BARKODLU ){
                if( $Parca->exists() && $Parca->get_details("durum") == Parca_Tipi::$AKTIF ){
                    $Parca->barkodlu_kullan();
                    $girenler_insert_array = array(
                        "form_gid" => $this->details["gid"],
                        "stok_kodu" => $giren["stok_kodu"]
                    );
                    if( isset( $giren["varyant_gid"] ) ) $girenler_insert_array["varyant_gid"] = $giren["varyant_gid"];
                    if( !$this->pdo->insert( DBT_ISEMRI_FORMU_GIRENLER, $girenler_insert_array) ){
                        $this->return_text = "Giren parça form içerik hata oluştu. İşlem iptal edildi 1.";
                        $this->ok = false;
                        return;
                    }
                } else {
                    $this->return_text = "Giren parça eklenirken hata oluştu.";
                    $this->ok = false;
                    return;
                }
            } else {
                if( $Parca->exists() ){
                    $Parca->barkodsuz_kullan( $giren["miktar"] );
                    $girenler_insert_array = array(
                        "form_gid"  => $this->details["gid"],
                        "stok_kodu" => $giren["stok_kodu"],
                        "miktar"    => $giren["miktar"]
                    );
                    if( isset( $giren["varyant_gid"] ) ) $girenler_insert_array["varyant_gid"] = $giren["varyant_gid"];
                    if( isset( $giren["ekleme"] ) ) $girenler_insert_array["ekleme"] = 1;
                    if( !$this->pdo->insert( DBT_ISEMRI_FORMU_GIRENLER, $girenler_insert_array) ){
                        $this->return_text = "Giren parça form içerik hata oluştu. İşlem iptal edildi 1.";
                        $this->ok = false;
                        return;
                    }
                }
            }
        }

        // aractan cikanlar
        foreach( $input_cikanlar as $cikan ){
            if( isset($cikan["parca_yok"]) ){

                $this->pdo->insert(DBT_ISEMRI_FORMU_CIKANLAR, array(
                    "form_gid"  => $this->details["gid"],
                    "stok_kodu" => "YOK",
                    "durum"     => Parca::$DBILGIYOK
                ));

            } else {
                if( $cikan["stok_kodu"] == "YOK" ){
                    // parçayi stoğa ekliyoruz
                    // giren parça reften parçatipini aliyoruz
                    $Ref_Parca = new Parca( $cikan["ref"]);
                    $Parca = new Parca();
                    $ekleme_array = array(
                        "parca_tipi"        => $Ref_Parca->get_details("parca_tipi"),
                        "aciklama"          => $cikan["aciklama"],
                        "fatura_no"         => 0,
                        "satici_firma"      => Data_Out::$BOS,
                        "parca_giris_gid"   => Data_Out::$BOS,
                        "durum"             => $cikan["durum"]
                    );
                    if( $cikan["varyant_gid"] != "YOK" ) $ekleme_array["varyant_gid"] = $cikan["varyant_gid"];
                    $Parca->barkodlu_ekle($ekleme_array);

                } else {
                    // stokta olan parça çıkışı
                    $Parca = new Parca($cikan["stok_kodu"]);
                }
                $Parca->durum_guncelle( $cikan["durum"] );
                if( $cikan["durum"] == Parca::$DREVIZE ){
                    $Revizyon_Talebi = new Revizyon_Talebi();
                    $Revizyon_Talebi->ekle(array(
                        "form_gid"              => $this->details["gid"],
                        "stok_kodu"             => $Parca->get_details("stok_kodu"),
                        "aciklama"              => "Revziyon talebi"
                    ));
                }
                $insert = $this->pdo->insert(DBT_ISEMRI_FORMU_CIKANLAR, array(
                    "form_gid"  => $this->details["gid"],
                    "stok_kodu" => $Parca->get_details("stok_kodu"),
                    "durum"     => $cikan["durum"]
                ));
                if( !$insert ){
                    $this->return_text = "Cıkan parça form içeriği eklenirken hata oluştu 2.";
                    $this->ok = false;
                }
            }
        }
        $this->return_text = "İş emri formu eklendi.";
    }

    public function girenleri_listele(){
        return $this->pdo->query("SELECT * FROM " . DBT_ISEMRI_FORMU_GIRENLER . " WHERE form_gid = ?", array( $this->details["gid"]) )->results();
    }

    public function form_cikanlari_listele(){
        return $this->pdo->query("SELECT * FROM " . DBT_ISEMRI_FORMU_CIKANLAR . " WHERE form_gid = ?", array( $this->details["gid"]) )->results();
    }

    public function personel_listele(){
        return $this->pdo->query("SELECT * FROM " . DBT_ISEMRI_FORMU_PERSONEL_DETAY . " WHERE form_gid = ?", array( $this->details["gid"]))->results();
    }

}