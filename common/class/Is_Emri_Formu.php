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

    public function detay_html(){
        $Surucu = new Personel($this->details["surucu"]);
        $Yapan = new Personel($this->details["giris_yapan"]);

        $cikanlar_html = "";
        foreach( $this->form_cikanlari_listele() as $cikan ){
            $Parca = new Parca( $cikan["stok_kodu"] );
            $Parca_Tipi = new Parca_Tipi( $Parca->get_details("parca_tipi") );
            $Satici_Firma = new Satici_Firma( $Parca->get_details("satici_firma"));
            $tooltip_data = 'Fatura No: ' . $Parca->get_details("fatura_no") . '<br> Satıcı Firma: ' . $Satici_Firma->get_details("isim");
            if( $cikan["durum"] == Parca::$DREVIZE || $cikan["durum"] == Parca::$DHURDA  ){
                $durum = "Hurda";
                if( $cikan["durum"] == Parca::$DREVIZE ){
                    $durum = "Revize";
                }
                $cikanlar_html .= '<tr>
                    <td>'.$Parca_Tipi->get_details("isim").'</td>
                    <td>'.$Parca->get_details("aciklama").'</td>
                    <td>'.$cikan["miktar"].' '.$Parca_Tipi->get_details("miktar_olcu_birimi").'</td>
                    <td title="'.$cikan["stok_kodu"].'">'.substr($cikan["stok_kodu"], 0, 25).'...</td>
                    <td>'.$durum.'</td>
                    <td><button type="button" class="mtbtn minitableico buyutec" onmouseover="Obarey_Tooltip(\'text\', \''.$tooltip_data.'\', this, event)" data-id="'.$cikan["stok_kodu"].'"></button></td>
                    </tr>';
            } else if( $cikan["durum"] == Parca::$DKAYIP ){
                $cikanlar_html .= '<tr>
                    <td>'.$Parca_Tipi->get_details("isim").'</td>
                    <td>'.$Parca->get_details("aciklama").'</td>
                    <td>'.$cikan["miktar"].' '.$Parca_Tipi->get_details("miktar_olcu_birimi").'</td>
                    <td title="'.$cikan["stok_kodu"].'">'.substr($cikan["stok_kodu"], 0, 25).'...</td>
                    <td>Parça çıkmadı / kayıp</td>
                    <td><button type="button" class="mtbtn minitableico buyutec" onmouseover="Obarey_Tooltip(\'text\', \''.$tooltip_data.'\', this, event)" data-id="'.$cikan["stok_kodu"].'"></button></td>
                    </tr>';
            } else if( $cikan["durum"] == Parca::$DBILGIYOK ){
                // stok kodu yerine takilan parçanın stok kodu
                // parca tipini burdan alicaz
                $cikanlar_html .= '<tr>
                    <td>'.$Parca_Tipi->get_details("isim").'</td>
                    <td>'.$Parca->get_details("aciklama").'</td>
                    <td>'.$cikan["miktar"].' '.$Parca_Tipi->get_details("miktar_olcu_birimi").'</td>
                    <td>YOK</td>
                    <td>Kaydı olmayan parça çıkmadı / kayıp</td>
                    <td></td>
                    </tr>';
            }
        }

        $girenler_html = "";
        foreach( $this->girenleri_listele() as $giren ){
            $Parca = new Parca($giren["stok_kodu"]);
            $Parca_Tipi = new Parca_Tipi( $Parca->get_details("parca_tipi") );
            $parca_adi = "";
            if( $Parca->get_details("varyant_gid") != Data_Out::$BOS ){
                // parent varyant var
                // alt varyant kontrolu yapiyoruz
                $Varyant_Parent = new Varyant( $Parca->get_details("varyant_gid"));
                $parca_adi = $Varyant_Parent->get_details("isim");
                if( isset($giren["varyant_gid"] ) ){
                    $Varyant_Alt = new Varyant( $giren["varyant_gid"] );
                    $parca_adi .= " - " . $Varyant_Alt->get_details("isim");
                }
            }

            $Satici_Firma = new Satici_Firma( $Parca->get_details("satici_firma"));
            $tooltip_data = 'Fatura No: ' . $Parca->get_details("fatura_no") . '<br> Satıcı Firma: ' . $Satici_Firma->get_details("isim");
            if( $giren["ekleme"] == 1 ) $tooltip_data .= " </br> Ekleme Yapıldı";

            $girenler_html .= '<tr>
                    <td>'.$Parca_Tipi->get_details("isim").'</td>
                    <td>'.$parca_adi.'</td>
                    <td>'.$Parca->get_details("aciklama").'</td>
                    <td>'.$giren["miktar"].' '.$Parca_Tipi->get_details("miktar_olcu_birimi").'</td>
                    <td title="'.$giren["stok_kodu"].'">'.substr($giren["stok_kodu"], 0, 25).'...</td>
                    <td><button type="button" onmouseover="Obarey_Tooltip(\'text\', \''.$tooltip_data.'\', this, event)" class="mtbtn minitableico buyutec"></button></td>
                    </tr>';
        }

        $personel_html = "";
        foreach( $this->personel_listele() as $personel ){
            $Personel = new Personel($personel["personel"] );
            $personel_html .= '<tr>
                            <td>'.$Personel->get_details("isim").'</td>
                            <td>'.substr($personel["is_tanimi"],0, 35).'</td>
                            <td>'.$personel["baslama"].'</td>
                            <td>'.$personel["bitis"].'</td>
                            <td><button type="button" onmouseover="Obarey_Tooltip(\'text\', \''.$personel["is_tanimi"].'\', this, event)" class="mtbtn minitableico buyutec"></button></td>
                        </tr>';

        }



        return '<div class="detay-popup">'
            . '<div class="input-row"><div class="input-col">'  . new Template_Detay_Cont( "Plaka / Kapı No", $this->details["plaka"] . " / " . $this->details["aktif_kapi_no"] ) . '</div>'
            . '<div class="input-row"><div class="input-col">'  . new Template_Detay_Cont( "Geliş KM", $this->details["gelis_km"] ) . '</div>'
            . '<div class="input-col">'  . new Template_Detay_Cont( "Giriş Yapan", $Yapan->get_details("isim") ) . '</div>'
            . '<div class="input-col">'  . new Template_Detay_Cont( "Sürücü", $Surucu->get_details("isim") ) . '</div> </div>'
            . new Template_Detay_Cont( "Geliş Tarih / Çıkış Tarih ", $this->details["gelis_tarih"] . " / " . $this->details["cikis_tarih"])


            . new Template_Detay_Cont( "Şikayet", $this->details["sikayet"] )
            . new Template_Detay_Cont( "Arıza Tespit", $this->details["ariza_tespit"] )
            . new Template_Detay_Cont( "Yapılan Onarım", $this->details["yapilan_onarim"] )
            . new Template_Detay_Cont( "Araç Yıkama / Kalibrasyon Yapıldı", Common::intevha($this->details["arac_yikama"]) . " / " . Common::intevha($this->details["kalibrasyon_yapildi"]) )
            . '
            <div class="input-container au ">
                <label>Giren Parçalar</label>
                <table class="obarey-table">
                    <thead>
                        <tr>
                            <td>Parça Tipi</td>
                            <td>Varyant</td>
                            <td>Açıklama</td>
                            <td>Miktar</td>
                            <td>Stok Kodu</td>
                            <td>Notlar</td>
                        </tr>
                    </thead>
                    <tbody>
                        '.$girenler_html.'
                    </tbody>
                </table>
            </div>
            
            <div class="input-container au ">
                <label>Çıkan Parçalar</label>
                <table class="obarey-table">
                    <thead>
                        <tr>
                            <td>Parça Tipi</td>
                            <td>Varyant</td>
                            <td>Açıklama</td>
                            <td>Miktar</td>
                            <td>Stok Kodu</td>
                            <td>Durum</td>
                            <td></td>
                        </tr>
                    </thead>
                    <tbody>
                        '.$cikanlar_html.'
                    </tbody>
                </table>
            </div>
            
            <div class="input-container au ">
                <label>Personel Detay</label>
                <table class="obarey-table">
                    <thead>
                        <tr>
                            <td>Personel</td>
                            <td>İş Tanımı</td>
                            <td>Başlangıç</td>
                            <td>Bitiş</td>
                            <td></td>
                        </tr>
                    </thead>
                    <tbody>
                        '.$personel_html.'
                    </tbody>
                </table>
            </div>
            
            
            </div>';
    }

}