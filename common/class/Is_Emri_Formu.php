<?php

/**
 * Created by PhpStorm.
 * User: Jeppe
 * Date: 07.03.2017
 * Time: 18:08
 */
class Is_Emri_Formu extends Data_Out{

    public static   $TAMAMLANDI = 1,
                    $TASLAK = 2;
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
        $this->details["gid"] = Gitas_Hash::hash_olustur(Gitas_Hash::$IS_EMRI_FORMU, array( "plaka" => $input_form_detaylari["plaka"] ) );
        //$this->details["gid"] = $input_form_detaylari["form_gid"];
        if( !$this->pdo->insert( $this->table, array(
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
        )) ){
            $this->return_text = "Form eklenirken bir hata oluştu.";
            $this->ok = false;
            return;
        }
        foreach( $input_personeL_detay as $personel ){
            if( !$this->pdo->insert(DBT_ISEMRI_FORMU_PERSONEL_DETAY, array(
                "form_gid"      => $this->details["gid"],
                "personel"      => $personel["personel"],
                "is_tanimi"     => $personel["is_tanimi"],
                "baslama"       => $personel["baslama"],
                "bitis"         => $personel["bitis"]
            )) ){
                $this->return_text = "Personel detay eklenirken hata oluştu.";
                $this->ok = false;
                $this->kaydi_geri_al();
                return;
            }
        }
        // araca girenler
        foreach( $input_girenler as $giren ){
            if( $giren["tip"] == Parca_Tipi::$BARKODLU ){
                $Barkodlu_Parca = new Barkodlu_Parca( $giren["stok_kodu"] );
                // parcanin degisiklik oncesi durumunu kaydediyoruz geri alabilmek için
                $this->stok_parcalar_eski_kayit[] = array(
                    "tip"           => Parca_Tipi::$BARKODLU,
                    "stok_kodu"     => $giren["stok_kodu"],
                    "hurda"         => $Barkodlu_Parca->get_details("hurda"),
                    "revize"        => $Barkodlu_Parca->get_details("revize"),
                    "kullanildi"    => $Barkodlu_Parca->get_details("kullanildi")
                );
                if( $Barkodlu_Parca->get_details("kullanildi") == 1 ){
                    $this->return_text = $giren["stok_kodu"] . " kodlu parça zaten kullanılmış. İşlem iptal edildi.";
                    $this->ok = false;
                    $this->kaydi_geri_al();
                    return;
                }
                if( $Barkodlu_Parca->get_details("hurda") == 1 ){
                    $this->return_text = $giren["stok_kodu"] . " kodlu parça hurda durumunda. İşlem iptal edildi.";
                    $this->ok = false;
                    $this->kaydi_geri_al();
                    return;
                }
                $Barkodlu_Parca->kullanildi_yap();
                if( !$Barkodlu_Parca->is_ok() ){
                    $this->return_text = "Giren parça eklenirken hata oluştu.";
                    $this->ok = false;
                    $this->kaydi_geri_al();
                    return;
                }


                if( !$this->form_giren_icerik_ekle( array("tip" => $giren["tip"], "stok_kodu" => $giren["stok_kodu"], "miktar" => 1 ) ) ){
                    $this->return_text = "Giren parça form içerik hata oluştu. İşlem iptal edildi 1.";
                    $this->ok = false;
                    $this->kaydi_geri_al();
                    return;
                }
            } else if( $giren["tip"] == Parca_Tipi::$BARKODSUZ ){

                // giriş - cikisi farkli olan parcalari burada ayikliyoruz
                if( $giren["stok_kodu"] == "GTSPATIPBALATABSARKASOL" ||  $giren["stok_kodu"] == "GTSPATIPBALATABSONSOL" ){
                    $stok_kodu =  "GTSPATIPBALATABSSOL";
                } else if( $giren["stok_kodu"] == "GTSPATIPBALATABSARKASAG" ||  $giren["stok_kodu"] == "GTSPATIPBALATABSONSAG" ){
                    $stok_kodu =  "GTSPATIPBALATABSSAG";
                } else {
                    // giriş - çıkışı aynı olan parcalar
                    $stok_kodu = $giren["stok_kodu"];
                }
                $Barkodsuz_Parca = new Barkodsuz_Parca( $stok_kodu );
                // stok miktarini kaydediyoruz hata durumunda geri almak için
                $this->stok_parcalar_eski_kayit[] = array(
                    "tip"                => Parca_Tipi::$BARKODSUZ,
                    "stok_kodu"          => $stok_kodu,
                    "eklenecek_miktar"   => $giren["miktar"] // stoga ekle yapicaz kaydi_geri_al da
                );
                $Barkodsuz_Parca->kullan( $giren["miktar"] );
                if( !$Barkodsuz_Parca->is_ok() ){
                    $this->return_text = "Giren parça eklenirken hata oluştu. Stokta yeterli malzeme olmayabilir (".$Barkodsuz_Parca->get_details("isim")."). İşlem iptal edildi.";
                    $this->ok = false;
                    $this->kaydi_geri_al();
                    return;
                }

                $ekleme = 0;
                if( isset($giren["ekleme"]) ) $ekleme = 1;
                if( !$this->form_giren_icerik_ekle( array( "tip" =>$giren["tip"], "stok_kodu" => $giren["stok_kodu"], "miktar" => $giren["miktar"], "ekleme" => $ekleme ) ) ){
                    $this->return_text = "Giren parça form içerik hata oluştu. İşlem iptal edildi 2.";
                    $this->ok = false;
                    $this->kaydi_geri_al();
                    return;
                }
            }
        }
        // aractan cikanlar
        foreach( $input_cikanlar as $cikan ){

            if( isset($cikan["parca_yok"] ) ){
                // aractan parca cikmamis ( araca takili olan stokta yok )
                if (!$this->form_cikan_icerik_ekle(Parca_Tipi::$BARKODLU, $cikan["ref"], Parca_Tipi::$CIKMADI )) {
                    $this->return_text = "Cıkan parça form içeriği eklenirken hata oluştu 2.";
                    $this->ok = false;
                    $this->kaydi_geri_al();
                    return;
                }
            } else {
                if( isset($cikan["garanti"] ) ){
                    // aractan cikan parca stokta yok

                    // parça tipini girenden aliyoruz
                    $Barkodlu_Parca_Ref = new Barkodlu_Parca($cikan["ref"]);
                    $Barkodlu_Parca = new Barkodlu_Parca();
                    $Barkodlu_Parca->ekle(array(
                        "aciklama"          => $cikan["aciklama"],
                        "tip"               => $Barkodlu_Parca_Ref->get_details("tip"),
                        "fatura_no"         => 0,
                        "satici_firma"      => "0",
                        "garanti_suresi"    => $cikan["garanti"],
                        "parca_giris_id"    => 0,
                        "kullanildi"        => 1,
                        "durum"             => 0
                    ));
                    if (!$Barkodlu_Parca->is_ok()) {
                        $this->return_text = "Cıkan parça eklenirken hata oluştu.";
                        $this->ok = false;
                        $this->kaydi_geri_al();
                        return;
                    }
                    $parca_gid = $Barkodlu_Parca->get_details("stok_kodu");
                    // gid ile parcaya ulasiyoruz db den
                    $Barkodlu_Parca = new Barkodlu_Parca($parca_gid);
                    // hata durumunda geri almak için stok kodunu kaydediyoruz
                    $this->stokta_olmayan_parca_stok_kodlari[] = $Barkodlu_Parca->get_details("stok_kodu");
                    if ($cikan["durum"] == "H") {
                        $Barkodlu_Parca->hurda_yap();
                    } else if ($cikan["durum"] == "R") {
                        $Barkodlu_Parca->revize_yap();
                        $Revizyon_Talebi = new Revizyon_Talebi();
                        $Revizyon_Talebi->ekle(array(
                            "form_gid"  => $this->details["gid"],
                            "stok_kodu" => $Barkodlu_Parca->get_details("stok_kodu"),
                            "aciklama"  => "Revizyon talebi"
                        ));
                    } else if( $cikan["durum"] == "Y" ){
                        // araca girilen parça kaybolmuş
                        $Barkodlu_Parca->kayip_yap();
                    }
                    if (!$this->form_cikan_icerik_ekle(Parca_Tipi::$BARKODLU, $Barkodlu_Parca->get_details("stok_kodu"), $cikan["durum"])) {
                        $this->return_text = "Cıkan parça form içeriği eklenirken hata oluştu 2.";
                        $this->ok = false;
                        $this->kaydi_geri_al();
                        return;
                    }
                } else {
                    // araca önceden takılan parçalardan biri çıkmış
                    $Barkodlu_Parca = new Barkodlu_Parca( $cikan["stok_kodu"] );
                    if( !$Barkodlu_Parca->exists() ){
                        $this->return_text = $cikan["stok_kodu"] . " böyle bir parça yok. İşlem iptal edildi.";
                        $this->ok = false;
                        $this->kaydi_geri_al();
                        return;
                    }
                    $this->stok_parcalar_eski_kayit[] = array(
                        "tip"           => Parca_Tipi::$BARKODLU,
                        "stok_kodu"     => $cikan["stok_kodu"],
                        "hurda"         => $Barkodlu_Parca->get_details("hurda"),
                        "revize"        => $Barkodlu_Parca->get_details("revize"),
                        "kullanildi"    => $Barkodlu_Parca->get_details("kullanildi")
                    );
                    if( $cikan["durum"] == "H" ){
                        $Barkodlu_Parca->hurda_yap();
                    } else if( $cikan["durum"] == "R" ){
                        $Barkodlu_Parca->revize_yap();
                        $Revizyon_Talebi = new Revizyon_Talebi();
                        $Revizyon_Talebi->ekle(array(
                            "form_gid"              => $this->details["gid"],
                            "stok_kodu"             => $Barkodlu_Parca->get_details("stok_kodu"),
                            "aciklama"              => "Revziyon talebi"
                        ));
                    } else if( $cikan["durum"] == "Y" ){
                        $Barkodlu_Parca->kayip_yap();
                    }
                    if( !$this->form_cikan_icerik_ekle( Parca_Tipi::$BARKODLU, $cikan["stok_kodu"], $cikan["durum"] ) ){
                        $this->return_text = "Cıkan parça form içeriği eklenirken hata oluştu 0.";
                        $this->ok = false;
                        $this->kaydi_geri_al();
                        return;
                    }
                }
            }
            //} else if( $cikan["tip"] == Parca_Tipi::$BARKODSUZ ){
                // v2 - barkodsuz giren parcalarin aynisi olacak o yuzden db ye bile kaydetmeye gerek yok
                // form goruntulenirken giren barkodsuzlardan çıkanları da buluruz
                // v1 - barkodsuz cikan parcayi hicbisi yapmiyoruz sadece forma kaydediyoruz
                /*if( !$this->form_cikan_icerik_ekle( $cikan["tip"], $cikan["stok_kodu"], $cikan["durum"], $cikan["miktar"] ) ){
                    $this->return_text = "Cıkan parça form içeriği eklenirken hata oluştu 1.";
                    $this->ok = false;
                    $this->kaydi_geri_al();
                    return;
                }*/
            //}


        }
        $this->return_text = "İş emri formu eklendi.";

    }

    /*** Hata durumunda tüm yaptığımız değişiklikleri geri aldığımız metod ***/
    private function kaydi_geri_al(){
        $this->pdo->query("DELETE FROM " . $this->table . " WHERE gid = ?", array($this->details["gid"]) );
        $this->pdo->query("DELETE FROM " . DBT_ISEMRI_FORMU_CIKANLAR . " WHERE form_gid = ?", array( $this->details["gid"] ) );
        $this->pdo->query("DELETE FROM " . DBT_ISEMRI_FORMU_GIRENLER . " WHERE form_gid = ?", array( $this->details["gid"] ) );
        $this->pdo->query("DELETE FROM " . DBT_ISEMRI_FORMU_PERSONEL_DETAY . " WHERE form_gid = ?", array( $this->details["gid"] ) );
        foreach( $this->stok_parcalar_eski_kayit as $parca ){
            if( $parca["tip"] == Parca_Tipi::$BARKODSUZ ){
                $Barkodsuz_Parca = new Barkodsuz_Parca($parca["stok_kodu"]);
                // eklenecek_miktar verisi araca giren parca sayisi, geri ekliyoruz onu
                $Barkodsuz_Parca->stok_ekle( $parca["eklenecek_miktar"] );
            } else if( $parca["tip"] == Parca_Tipi::$BARKODLU ){
                $Barkodlu_Parca = new Barkodlu_Parca( $parca["stok_kodu"] );
                $Barkodlu_Parca->form_verisi_guncelleme(array(
                    "hurda"         => $parca["hurda"],
                    "revize"        => $parca["revize"],
                    "kullanildi"    => $parca["kullanildi"]
                ));
            }
        }
        foreach( $this->stokta_olmayan_parca_stok_kodlari as $stok_kodu ){
            $Barkodlu_Parca = new Barkodlu_Parca( $stok_kodu );
            $Barkodlu_Parca->sil();
        }
    }
    private function form_giren_icerik_ekle( $data ){
        if( $data["tip"] == Parca_Tipi::$BARKODSUZ ){
            return $this->pdo->insert( DBT_ISEMRI_FORMU_GIRENLER, array(
                "form_gid"  => $this->details["gid"],
                "tip"       => $data["tip"],
                "stok_kodu" => $data["stok_kodu"],
                "miktar"    => $data["miktar"],
                "ekleme"    => $data["ekleme"]
            ));
        } else if( $data["tip"] == Parca_Tipi::$BARKODLU ){
            return $this->pdo->insert( DBT_ISEMRI_FORMU_GIRENLER, array(
                "form_gid"  => $this->details["gid"],
                "tip"       => $data["tip"],
                "stok_kodu" => $data["stok_kodu"],
                "miktar"    => $data["miktar"]
            ));
        }
    }
    private function form_cikan_icerik_ekle($tip, $stok_kodu, $durum, $miktar = 1 ){
        if( $durum == "H" ) $durum = Parca_Tipi::$HURDA;
        if( $durum == "R" ) $durum = Parca_Tipi::$REVIZE;
        if( $durum == "Y" ) $durum = Parca_Tipi::$CIKMADI_STOK;
        return $this->pdo->insert( DBT_ISEMRI_FORMU_CIKANLAR, array(
            "form_gid"  => $this->details["gid"],
            "tip"       => $tip,
            "stok_kodu" => $stok_kodu,
            "durum"     => $durum,
            "miktar"    => $miktar
        ));
    }

    public function form_girenleri_listele( $ozet = false ){
        $output = array();
        $query = $this->pdo->query("SELECT * FROM " . DBT_ISEMRI_FORMU_GIRENLER . " WHERE form_gid = ?", array( $this->details["gid"]) )->results();
        foreach( $query as $parca ){
            $Parca = new Barkodlu_Parca($parca["stok_kodu"]);
            // listelencek parçalar araçta aktif takili olanlar olacak
            // onceden takilmis daha sonra cikarilmis, hurda olmus veya kaybolmus parcalari listelemiyeceğiz
            if( $ozet ){
                $output[] = $parca;
            } else {
                if( $Parca->exists() && $Parca->get_details("kayip") != 1 && $Parca->get_details("hurda") != 1  ){
                    $output[] = $parca;
                }
            }
        }
        return $output;
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
            $Parca = Parca::get( $cikan["stok_kodu"] );

            $Parca_Tipi = new Parca_Tipi( $Parca->get_details("tip") );
            $Satici_Firma = new Satici_Firma( $Parca->get_details("satici_firma"));
            $tooltip_data = 'Fatura No: ' . $Parca->get_details("fatura_no") . '<br> Satıcı Firma: ' . $Satici_Firma->get_details("isim");
            if( $cikan["durum"] == Parca_Tipi::$REVIZE || $cikan["durum"] == Parca_Tipi::$HURDA ){
                $durum = "Hurda";
                if( $cikan["durum"] == Parca_Tipi::$REVIZE ){
                    $durum = "Revize";
                }
                $cikanlar_html .= '<tr>
                    <td>'.$Parca_Tipi->get_details("isim").'</td>
                    <td>'.$Parca->get_details("aciklama").'</td>
                    <td>'.$cikan["miktar"].' '.$Parca_Tipi->get_details("miktar_olcu_birimi").'</td>
                    <td title="'.$cikan["stok_kodu"].'">'.substr($cikan["stok_kodu"], 0, 25).'...</td>
                    <td>'.$durum.'</td>
                    <td><button type="button" class="mtbtn minitableico buyutec"  onmouseover="Obarey_Tooltip(\'text\', \''.$tooltip_data.'\', this, event)" data-id="'.$cikan["stok_kodu"].'"></button></td>
                    </tr>';
            } else if( $cikan["durum"] == Parca_Tipi::$CIKMADI_STOK ){
                $cikanlar_html .= '<tr>
                    <td>'.$Parca_Tipi->get_details("isim").'</td>
                    <td>'.$Parca->get_details("aciklama").'</td>
                    <td>'.$cikan["miktar"].' '.$Parca_Tipi->get_details("miktar_olcu_birimi").'</td>
                    <td title="'.$cikan["stok_kodu"].'">'.substr($cikan["stok_kodu"], 0, 25).'...</td>
                    <td>Parça çıkmadı / kayıp</td>
                    <td><button type="button" class="mtbtn minitableico buyutec" onmouseover="Obarey_Tooltip(\'text\', \''.$tooltip_data.'\', this, event)" data-id="'.$cikan["stok_kodu"].'"></button></td>
                    </tr>';
            } else if( $cikan["durum"] == Parca_Tipi::$CIKMADI ){
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
        foreach( $this->form_girenleri_listele(true) as $giren ){
            $Parca = Parca::get($giren["stok_kodu"]);
            if( $giren["tip"] == Parca_Tipi::$BARKODLU ){
                $parca_adi = $Parca->get_details("aciklama");

                $Satici_Firma = new Satici_Firma( $Parca->get_details("satici_firma"));
                $tooltip_data = 'Fatura No: ' . $Parca->get_details("fatura_no") . '<br> Satıcı Firma: ' . $Satici_Firma->get_details("isim");
            } else {
                $parca_adi = $Parca->get_details("aciklama");
                $tooltip_data = "";
                if( $giren["ekleme"] == 1 ){
                    $tooltip_data = "Ekleme Yapıldı.";
                }

            }
            $Parca_Tipi = new Parca_Tipi( $Parca->get_details("tip") );
            $girenler_html .= '<tr>
                    <td>'.$Parca_Tipi->get_details("isim").'</td>
                    <td>'.$parca_adi.'</td>
                    <td>'.$giren["miktar"].' '.$Parca_Tipi->get_details("miktar_olcu_birimi").'</td>
                    <td title="'.$giren["stok_kodu"].'">'.substr($giren["stok_kodu"], 0, 25).'...</td>
                    <td><button type="button" ttdata="'.$tooltip_data.'"  onmouseover="Obarey_Tooltip(\'text\', \''.$tooltip_data.'\', this, event)" class="mtbtn minitableico buyutec"></button></td>
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
                            <td><button type="button" ttdata="'.$tooltip_data.'"  onmouseover="Obarey_Tooltip(\'text\', \''.$personel["is_tanimi"].'\', this, event)" class="mtbtn minitableico buyutec"></button></td>
                        </tr>';

        }



        $genel_info = array(
            array(
                "header" => "FORM DETAYLARI",
                "items"  => array(
                    array( "key" => "PLAKA", "val" => $this->details["plaka"]  ),
                    array( "key" => "KAPI NO", "val" => $this->details["aktif_kapi_no"] ),
                    array( "key" => "SÜRÜCÜ", "val" => $Surucu->get_details("isim") ),
                    array( "key" => "GELİŞ KM", "val" => $this->details["gelis_km"] ),
                    array( "key" => "GELİŞ TARİH", "val" => $this->details["gelis_tarih"] ),
                    array( "key" => "ÇIKIŞ TARİH", "val" => $this->details["cikis_tarih"] ),
                    array( "key" => "ŞİKAYET", "val" => $this->details["sikayet"] ),
                    array( "key" => "ARIZA TESPİT", "val" => $this->details["ariza_tespit"] ),
                    array( "key" => "YAPILAN ONARIM", "val" => $this->details["yapilan_onarim"] ),
                    array( "key" => "ARAÇ YIKANDI", "val" => Common::intevha($this->details["arac_yikama"]) ),
                    array( "key" => "KALİBRASYON YAPILDI", "val" => Common::intevha($this->details["kalibrasyon_yapildi"]) ),
                    array( "key" => "GİRİŞ YAPAN", "val" => $Yapan->get_details("isim") )
                )
            )
        );

        return '<div class="detay-popup">'
            . Popup_Stats::init( $genel_info, Popup_Stats::$OFF_POPUP )
            . '
            <div class="input-container au ">
                <label>Giren Parçalar</label>
                <table class="obarey-table">
                    <thead>
                        <tr>
                            <td>Parça Tipi</td>
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
            <a href="'.URL_YAZDIRMA_TEMA_IEF.'?form_gid='.$this->details["gid"].'" target="_blank" class="mnbtn mor yazdirbtn">YAZDIR</a>
            <a href="'.URL_YAZDIRMA_TEMA_IEF_CIKANLAR.'?form_gid='.$this->details["gid"].'" target="_blank" class="mnbtn mor yazdirbtn">ÇIKANLAR BARKOD YAZDIR</a>
            
            </div>';
    }

}