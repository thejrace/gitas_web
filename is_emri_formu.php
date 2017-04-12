<?php
/**
 * Created by PhpStorm.
 * User: Jeppe
 * Date: 21.03.2017
 * Time: 15:23
 */

    require 'inc/init.php';

    $KISAYOL_PARCALAR = array(
        "Balata" => array(
            "Sağ Ön"        => "GTSPATIPBALATABSONSAG",
            "Sol Ön"        => "GTSPATIPBALATABSONSOL",
            "Sağ Arka"      => "GTSPATIPBALATABSARKASAG",
            "Sol Arka"      => "GTSPATIPBALATABSARKASOL"
        ),
        "Yağ" => array(
            "Diferansiyel"  => "GTSPATIPYAGBSDIFERANSIYEL",
            "Motor"         => "GTSPATIPYAGBSMOTOR",
            "Şanzıman"      => "GTSPATIPYAGBSSANZIMAN",
            "Direksiyon"    => "GTSPATIPYAGBSDIREKSIYON",
            "Gres"          => "GTSPATIPYAGBSGRES",
            "Sıvı Gres"     => "GTSPATIPYAGBSSIVIGRES"
        ),
        "Muhtelif" => array(
            "Antifriz"      => "GTSPATIPANTIFRIZBSANTIFRIZ",
            "Balata Spreyi" => "GTSPATIPBALATASPREYIBSBALATASPREYI",
            "Bant"          => "GTSPATIPBANTBSBANT",
            "Silikon"       => "GTSPATIPSILIKONBSSILIKON"
        )
    );

    $Balata_Sag = new Barkodsuz_Parca( "GTSPATIPBALATABSSAG" );
    $Balata_Sol = new Barkodsuz_Parca( "GTSPATIPBALATABSSOL" );
    $Diferansiyel_Yag = new Barkodsuz_Parca( $KISAYOL_PARCALAR["Yağ"]["Diferansiyel"] );
    $Motor_Yag = new Barkodsuz_Parca( $KISAYOL_PARCALAR["Yağ"]["Motor"] );
    $Sanziman_Yag = new Barkodsuz_Parca( $KISAYOL_PARCALAR["Yağ"]["Şanzıman"] );
    $Direksiyon_Yag = new Barkodsuz_Parca( $KISAYOL_PARCALAR["Yağ"]["Direksiyon"] );
    $Gres_Yag = new Barkodsuz_Parca( $KISAYOL_PARCALAR["Yağ"]["Gres"] );
    $Sıvı_Gres_Yag = new Barkodsuz_Parca( $KISAYOL_PARCALAR["Yağ"]["Sıvı Gres"] );
    $Antifriz = new Barkodsuz_Parca( $KISAYOL_PARCALAR["Muhtelif"]["Antifriz"] );
    $Balata_Spreyi = new Barkodsuz_Parca( $KISAYOL_PARCALAR["Muhtelif"]["Balata Spreyi"] );
    $Bant = new Barkodsuz_Parca( $KISAYOL_PARCALAR["Muhtelif"]["Bant"] );
    $Silikon = new Barkodsuz_Parca( $KISAYOL_PARCALAR["Muhtelif"]["Silikon"] );


    $KISAYOL_PARCALAR_STOK = array(
        "Balata" => array(
            "Sağ"        => 'Sağ Balata Stok Miktarı: ' . $Balata_Sag->get_details("miktar"),
            "Sol"        => 'Sol Balata Stok Miktarı: ' . $Balata_Sol->get_details("miktar")
        ),
        "Yağ" => array(
            "Diferansiyel"  => 'Diferansiyel Yağı Stok Miktarı: '. $Diferansiyel_Yag->get_details("miktar"),
            "Motor"         => 'Motor Yağı Stok Miktarı: ' . $Motor_Yag->get_details("miktar"),
            "Şanzıman"      => 'Şanzıman Yağı Stok Miktarı: ' . $Sanziman_Yag->get_details("miktar"),
            "Direksiyon"    => 'Direksiyon Yağı Stok Miktarı: ' . $Direksiyon_Yag->get_details("miktar"),
            "Gres"          => 'Gres Yağı Stok Miktarı: ' . $Gres_Yag->get_details("miktar"),
            "Sıvı Gres"     => 'Sıvı Gres Yağı Stok Miktarı: ' . $Sıvı_Gres_Yag->get_details("miktar")
        ),
        "Muhtelif" => array(
            "Antifriz"      => 'Antifriz Stok Miktarı: ' . $Antifriz->get_details("miktar"),
            "Balata Spreyi" => 'Balata Spreyi Yağ Stok Miktarı: ' . $Balata_Spreyi->get_details("miktar"),
            "Bant"          => 'Bant Stok Miktarı: ' . $Bant->get_details("miktar"),
            "Silikon"       => 'Silikon Stok Miktarı: ' . $Silikon->get_details("miktar")
        )
    );


    $PARCA_TIPLERI = DB::getInstance()->query("SELECT * FROM " . DBT_PARCA_TIPLERI )->results();
    $PARCA_TIPLERI_BARKODSUZ        = array();
    $PARCA_TIPLERI_BARKODLU         = array();
    $GIREN_PARCA_BARKODSUZ          = array();
    $PARCA_TIPLERI_TALEP            = array();
    $PARCA_TIPLERI_GIREN_ELLE_GIRIS = array();
    foreach( $PARCA_TIPLERI as $tip ){
        if( $tip["tip"] == Parca_Tipi::$BARKODSUZ ) $PARCA_TIPLERI_BARKODSUZ[] = $tip;
        if( $tip["tip"] == Parca_Tipi::$BARKODLU ) $PARCA_TIPLERI_BARKODLU[] = $tip;
        if( $tip["isim"] != "Balata" &&
            $tip["isim"] != "Yağ" &&
            $tip["isim"] != "Antifriz" &&
            $tip["isim"] != "Balata Spreyi" &&
            $tip["isim"] != "Bant" &&
            $tip["isim"] != "Silikon"
        ) $PARCA_TIPLERI_GIREN_ELLE_GIRIS[] = $tip;
    }
    foreach( $PARCA_TIPLERI_GIREN_ELLE_GIRIS as $tip ) if( $tip["tip"] == Parca_Tipi::$BARKODSUZ )  $GIREN_PARCA_BARKODSUZ[] = $tip;
    $SERVIS_PERSONEL = DB::getInstance()->query("SELECT * FROM " . DBT_PERSONEL . " WHERE seviye = ?", array( Personel::$SERVIS ) )->results();

    $SURUCULER = DB::getInstance()->query("SELECT * FROM " . DBT_PERSONEL . " WHERE seviye = ?", array( Personel::$SURUCU ) )->results();

    /**

     *  PARÇA ÇIKIŞ ÖNCEKİLERDEN SEÇME:
     *      Otobüse önceden takılmış ve kaydımız da olan BARKODLU parçalari listelicez.
     *
     *  PARÇA ÇIKIŞ ELLE SEÇME:
     *      Barkodlu, Barkodsuz ve kısayolda olmayan parça tiplerini listeliyoruz. Bundaki amaç kaydı olmayan parçaları da kayıt altına almak.
     *
     *  PARÇA GİRİŞ BARKODLA:
     *      Barkodlu parçalar için geçerli yalnızca, kontrol edip verisini alıcaz.
     *
     *  PARÇA GİRİŞ KISAYOLLA:
     *      Listelediğimiz yağ, balata vs. inputlarından veri alicaz
     *
     *  PARÇA GİRİŞ BARKODSUZ:
     *      Barkodsuz ve kısayolda olmayan parçları listeleyip veri alıcaz.



     **/

    $TITLE = "İş Emri Formu Oluştur";
    require 'inc/header.php';
?>


    <div class="section-form">
        <div class="form">
            <form action="" method="post" id="ief">
                <input type="hidden" name="form_gid" id="form_gid" />
                <div class="section arac-section">
                    <div class="section-header">
                        <label>Araç Bilgileri</label>
                    </div>
                    <div class="section-content">

                        <div class="form-row">
                            <div class="form-col">
                                <div class="input-container au">
                                    <label for="ief_plaka">Plaka</label>
                                    <input type="text" class="req" name="plaka" id="ief_plaka" value="34 "/>
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-col">
                                <div class="input-container au">
                                    <label for="ief_ruhsat_kapi_kodu">Ruhsat Kapı No</label>
                                    <input type="text" class="req" name="ruhsat_kapi_kodu" id="ief_ruhsat_kapi_kodu" />
                                </div>
                            </div>

                            <div class="form-col">
                                <div class="input-container au">
                                    <label for="ief_gelis_km">Geliş KM</label>
                                    <input type="text" class="req" name="gelis_km" id="ief_gelis_km" />
                                </div>
                            </div>

                            <div class="form-col">
                                <div class="input-container au">
                                    <label for="ief_gelis_tarih">Geliş Tarih</label>
                                    <input type="text" class="req" name="gelis_tarih" id="ief_gelis_tarih" />
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-col">
                                <div class="input-container au">
                                    <label for="ief_aktif_kapi_kodu">Aktif Kapı No</label>
                                    <input type="text" class="req" name="aktif_kapi_kodu" id="ief_aktif_kapi_kodu" />
                                </div>
                            </div>

                            <div class="form-col">
                                <div class="input-container au">
                                    <label for="ief_surucu">Sürücü</label>
                                    <select name="surucu" id="ief_surucu" class="select_no_zero">
                                        <option value="0">Seçiniz...</option>
                                        <?php
                                            foreach( $SURUCULER as $surucu ){
                                                $Personel = new Personel($surucu["gid"]);
                                                echo '<option value="'.$Personel->get_details("gid").'">'.$Personel->get_details("isim").'</option>';
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-col">
                                <div class="input-container au">
                                    <label for="ief_cikis_tarih">Çıkış Tarih</label>
                                    <input type="text" class="req" name="cikis_tarih" id="ief_cikis_tarih" />
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="section detay-section">
                    <div class="section-header">
                        <label>Arıza Detayları</label>
                    </div>
                    <div class="section-content">
                        <div class="form-row">
                            <div class="form-col full">
                                <div class="input-container au">
                                    <label for="ief_sikayet">Şikayet</label>
                                    <textarea class="req full" name="sikayet" id="ief_sikayet" ></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-col full">
                                <div class="input-container au">
                                    <label for="ief_ariza_tespit">Arıza Tespiti ve Nedeni</label>
                                    <textarea class="req full" name="ariza_tespit" id="ief_ariza_tespit" ></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-col full">
                                <div class="input-container au">
                                    <label for="ief_yapilan_onarim">Yapılan Onarım / İyileştirme Önerisi</label>
                                    <textarea class="req full" name="yapilan_onarim" id="ief_yapilan_onarim" ></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="section stok-section">
                    <div class="section-header">
                        <label>Kullanılan Stok - Malzeme</label>
                    </div>
                    <div class="section-content">

                        <div class="float-form clearfix">

                            <div class="form-section-33">
                                <div class="form-section-header kisa">BALATA</div>
                                <div class="form-section-content">

                                    <div class="input-container au cb" onmouseover="Obarey_Tooltip('text', '<?php echo $KISAYOL_PARCALAR_STOK["Balata"]["Sağ"] ?>', this, event)">
                                        <input type="checkbox" class="kisayol-stok-cb" id="bal0" stok_kodu="<?php echo $KISAYOL_PARCALAR["Balata"]["Sağ Ön"] ?>"/>
                                        <label for="bal0">Sağ Ön</label>
                                    </div>

                                    <div class="input-container au cb" onmouseover="Obarey_Tooltip('text', '<?php echo $KISAYOL_PARCALAR_STOK["Balata"]["Sağ"] ?>', this, event)">
                                        <input type="checkbox" class="kisayol-stok-cb" id="bal1" stok_kodu="<?php echo $KISAYOL_PARCALAR["Balata"]["Sağ Arka"] ?>" />
                                        <label for="bal1">Sağ Arka</label>
                                    </div>


                                    <div class="input-container au cb" onmouseover="Obarey_Tooltip('text', '<?php echo $KISAYOL_PARCALAR_STOK["Balata"]["Sol"] ?>', this, event)">
                                        <input type="checkbox" class="kisayol-stok-cb" id="bal2" stok_kodu="<?php echo $KISAYOL_PARCALAR["Balata"]["Sol Ön"] ?>"/>
                                        <label for="bal2">Sol Ön</label>
                                    </div>

                                    <div class="input-container au cb" onmouseover="Obarey_Tooltip('text', '<?php echo $KISAYOL_PARCALAR_STOK["Balata"]["Sol"] ?>', this, event)">
                                        <input type="checkbox" class="kisayol-stok-cb" id="bal3" stok_kodu="<?php echo $KISAYOL_PARCALAR["Balata"]["Sol Arka"] ?>" />
                                        <label for="bal3">Sol Arka</label>
                                    </div>

                                </div>
                            </div>
                            <div class="form-section-33">
                                <div class="form-section-header kisa">YAĞ</div>
                                <div class="form-section-content">

                                    <div class="input-row">
                                        <div class="input-container au" onmouseover="Obarey_Tooltip('text', '<?php echo $KISAYOL_PARCALAR_STOK["Yağ"]["Diferansiyel"] ?>', this, event)">
                                            <label>Diferansiyel</label>
                                            <input type="text" class="kisa posnum kisayol-stok-input" stok_kodu="<?php echo $KISAYOL_PARCALAR["Yağ"]["Diferansiyel"] ?>" />
                                        </div>

                                        <div class="input-container au" onmouseover="Obarey_Tooltip('text', '<?php echo $KISAYOL_PARCALAR_STOK["Yağ"]["Motor"] ?>', this, event)">
                                            <label>Motor</label>
                                            <input type="text" class="kisa posnum kisayol-stok-input" stok_kodu="<?php echo $KISAYOL_PARCALAR["Yağ"]["Motor"] ?>" />
                                        </div>
                                    </div>

                                    <div class="input-row">
                                        <div class="input-container au" onmouseover="Obarey_Tooltip('text', '<?php echo $KISAYOL_PARCALAR_STOK["Yağ"]["Şanzıman"] ?>', this, event)">
                                            <label>Şanzıman</label>
                                            <input type="text" class="kisa posnum kisayol-stok-input" stok_kodu="<?php echo $KISAYOL_PARCALAR["Yağ"]["Şanzıman"] ?>"/>
                                        </div>

                                        <div class="input-container au" onmouseover="Obarey_Tooltip('text', '<?php echo $KISAYOL_PARCALAR_STOK["Yağ"]["Direksiyon"] ?>', this, event)">
                                            <label>Direksiyon</label>
                                            <input type="text" class="kisa posnum kisayol-stok-input" stok_kodu="<?php echo $KISAYOL_PARCALAR["Yağ"]["Direksiyon"] ?>" />
                                        </div>
                                    </div>

                                    <div class="input-row">
                                        <div class="input-container au" onmouseover="Obarey_Tooltip('text', '<?php echo $KISAYOL_PARCALAR_STOK["Yağ"]["Gres"] ?>', this, event)">
                                            <label>Gres</label>
                                            <input type="text" class="kisa posnum kisayol-stok-input" stok_kodu="<?php echo $KISAYOL_PARCALAR["Yağ"]["Gres"] ?>" />
                                        </div>

                                        <div class="input-container au" onmouseover="Obarey_Tooltip('text', '<?php echo $KISAYOL_PARCALAR_STOK["Yağ"]["Sıvı Gres"] ?>', this, event)">
                                            <label>Sıvı Gres</label>
                                            <input type="text" class="kisa posnum kisayol-stok-input" stok_kodu="<?php echo $KISAYOL_PARCALAR["Yağ"]["Sıvı Gres"] ?>"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-section-33">
                                <div class="form-section-header kisa">MUHTELİF</div>
                                <div class="form-section-content">

                                    <div class="input-row">
                                        <div class="input-container au" onmouseover="Obarey_Tooltip('text', '<?php echo $KISAYOL_PARCALAR_STOK["Muhtelif"]["Antifriz"] ?>', this, event)">
                                            <label>Antifriz</label>
                                            <input type="text" class="kisa posnum kisayol-stok-input" stok_kodu="<?php echo $KISAYOL_PARCALAR["Muhtelif"]["Antifriz"] ?>" />
                                        </div>

                                        <div class="input-container au" onmouseover="Obarey_Tooltip('text', '<?php echo $KISAYOL_PARCALAR_STOK["Muhtelif"]["Balata Spreyi"] ?>', this, event)">
                                            <label>Balata Spreyi</label>
                                            <input type="text" class="kisa posnum kisayol-stok-input" stok_kodu="<?php echo $KISAYOL_PARCALAR["Muhtelif"]["Balata Spreyi"] ?>"/>
                                        </div>
                                    </div>

                                    <div class="input-row">
                                        <div class="input-container au" onmouseover="Obarey_Tooltip('text', '<?php echo $KISAYOL_PARCALAR_STOK["Muhtelif"]["Silikon"] ?>', this, event)">
                                            <label>Silikon</label>
                                            <input type="text" class="kisa posnum kisayol-stok-input" stok_kodu="<?php echo $KISAYOL_PARCALAR["Muhtelif"]["Silikon"] ?>" />
                                        </div>

                                        <div class="input-container au" onmouseover="Obarey_Tooltip('text', '<?php echo $KISAYOL_PARCALAR_STOK["Muhtelif"]["Bant"] ?>', this, event)">
                                            <label>Bant</label>
                                            <input type="text" class="kisa posnum kisayol-stok-input" stok_kodu="<?php echo $KISAYOL_PARCALAR["Muhtelif"]["Bant"] ?>"/>
                                        </div>
                                    </div>


                                </div>

                            </div>
                        </div>

                        <div class="form-section-header full mtop-20">STOK</div>
                        <div class="form-section-nav">
                            <button type="button" class="mnbtn acikgri cikan-parca-btn">ÇIKAN PARÇA</button>
                            <button type="button" class="mnbtn acikgri giren-parca-btn">GİREN PARÇA</button>
                        </div>

                        <div class="datatable">
                            <div class="dtfilter">
                            </div>

                            <div class="dtcontent">
                                <ul class="stok-dt">

                                </ul>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="section personel-section">
                    <div class="section-header">
                        <label>Personel Bilgisi</label>
                    </div>
                    <div class="section-content">
                        <div class="form-section-nav">
                            <button type="button" class="mnbtn acikgri personel-ekle">PERSONEL EKLE</button>
                        </div>

                        <div class="datatable">
                            <div class="dtfilter">
                            </div>

                            <div class="dtcontent">
                                <ul class="personel-dt">


                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="nav">
                        <button type="button" class="mnbtn gri taslak">TASLAK OLARAK KAYDET</button>
                        <button type="button" class="mnbtn mor tamamla">TAMAMLA VE YAZDIR</button>
                    </div>
                </div>


                <input type="hidden" name="req" value="is_emri_formu_ekle"/>
            </form>
        </div>
    </div>


    <script type="text/template" data-template="giren_parca_part_1">
        <div class="popup-form">
            <div class="form">
                <button type="button" class="mnbtn mor giren_parca_barkod">BARKOD</button>
                <button type="button" class="mnbtn mor giren_parca_barkodsuz">BARKODSUZ</button>
                <button type="button" class="mnbtn mor giren_parca_parca_talep">PARÇA TALEP</button>
            </div>
        </div>
    </script>
    <script type="text/template" data-template="giren_parca_barkod">
        <div class="popup-form">
            <div class="form">
                <form action="" method="post" id="giren_parca_barkod_form">
                    <div class="form-notf"></div>
                    <div class="info">Barkodu Okutun</div>
                    <div class="input-container">
                        <input type="text" class="barkod-input req" />
                    </div>
                    <div class="notf"></div>
                    <button class="mnbtn mor">TAMAM</button>
                </form>
            </div>

        </div>
    </script>
    <script type="text/template" data-template="giren_parca_barkodsuz">
        <div class="popup-form">
            <div class="form">
                <div class="form-notf"></div>
                <form action="" method="POST" id="giren_parca_barkodsuz_form">
                    <div class="form-row">
                        <div class="input-container au">
                            <label for="parca_tipi">Parça Tipi</label>
                            <select name="parca_tipi" class="parca_tipi select_no_zero">
                                <option value="0">Seçiniz...</option>
                                <?php
                                foreach( $GIREN_PARCA_BARKODSUZ as $tip ){
                                    $Parca_Tipi = new Parca_Tipi( $tip["gid"] );
                                    echo "<option value='" . $Parca_Tipi->get_details("gid") . "' tip='".$Parca_Tipi->get_details("tip")."'>" . $Parca_Tipi->get_details("isim") . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-row varyant-append">

                    </div>

                    <div class="form-row">
                        <div class="input-container au">
                            <label for="miktar">Miktar</label>
                            <input type="text" id="miktar" class="req posnum" name="miktar" value="1" />
                        </div>
                    </div>
                    <div class="form-row">
                        <button class="mnbtn mor">Ekle</button>
                    </div>
                </form>
            </div>
        </div>
    </script>
    <script type="text/template" data-template="cikan_parca_elle_giris">
        <div class="popup-form">
            <div class="form">
                <div class="form-notf"></div>
                <form action="" method="POST" id="cikan_parca_form">
                    <div class="form-row">
                        <div class="input-container au">
                            <label for="parca_tipi">Parça Tipi</label>
                            <select name="parca_tipi" class="select_no_zero parca_tipi">
                                <option value="0">Seçiniz...</option>
                                <?php
                                foreach( $PARCA_TIPLERI_GIREN_ELLE_GIRIS as $tip ){
                                    $Parca_Tipi = new Parca_Tipi( $tip["gid"] );
                                    echo "<option value='" . $Parca_Tipi->get_details("gid") . "' tip='".$Parca_Tipi->get_details("tip")."'>" . $Parca_Tipi->get_details("isim") . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-row varyant-append">

                    </div>

                    <div class="form-row">
                        <div class="form-col">
                            <div class="input-container au cb">
                                <input type="radio" durum="H" class="req" id="hurda-cb" name="durum-cb" />
                                <label for="hurda-cb">Hurda</label>
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="input-container au cb">
                                <input type="radio" durum="R" id="revize-cb" name="durum-cb" />
                                <label for="revize-cb" >Revize</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="input-container au">
                            <label for="miktar">Miktar</label>
                            <input type="text" id="miktar" class="req posnum" name="miktar" value="1" />
                        </div>
                    </div>
                    <div class="form-row">
                        <button class="mnbtn mor">Ekle</button>
                    </div>
                </form>
            </div>
        </div>

    </script>
    <script type="text/template" data-template="cikan_parca_part_1">
        <div class="popup-form">
            <div class="form">
                <div class="form-notf"></div>
                <form action="" method="POST" id="cikan_parca_form">
                    <div class="info csari fs12">Otobüse önceden takılan parçaları listelemek için Parça Tipini seçin.<br>

                        Eğer çıkan parçanın kaydı listede yoksa <button type="button" class="mnbtn mor cikan_parca_elle_giris">Elle Giriş</button> yapın </div>
                    <div class="form-row">
                        <div class="input-container au">
                            <label for="parca_tipi">Parça Tipi</label>
                            <select name="parca_tipi" id="parca_tipi_cikan" class="select_no_zero">
                                <option value="0">Seçiniz...</option>
                                <?php
                                foreach( $PARCA_TIPLERI_BARKODLU as $tip ){
                                    $Parca_Tipi = new Parca_Tipi( $tip["gid"] );
                                    echo "<option value='" . $Parca_Tipi->get_details("gid") . "' tip='".$Parca_Tipi->get_details("tip")."'>" . $Parca_Tipi->get_details("isim") . "</option>";
                                }
                                ?>
                            </select>
                        </div>

                    </div>

                    <div style="background:#3d3d3d;" class="minitable-container">

                    </div>

                </form>
            </div>
        </div>
    </script>
    <script type="text/template" data-template="parca_talep">
        <div class="popup-form">
            <div class="form">
                <div class="info csari">Parça Talep</div>
                <div class="form-notf"></div>
                <form action="" method="POST" id="parca_talep_form">
                    <input type="hidden" name="req" value="parca_talep" />
                    <div class="form-row">
                        <div class="input-container au">
                            <label for="parca_talep_form_parca_tipi">Parça Tipi</label>
                            <select name="parca_tipi" class="parca_tipi select_no_zero" id="parca_talep_form_parca_tipi">
                                <option value="0">Seçiniz...</option>
                                <?php
                                foreach( $PARCA_TIPLERI as $tip ){
                                    $Parca_Tipi = new Parca_Tipi( $tip["gid"] );
                                    echo "<option value='" . $Parca_Tipi->get_details("gid") . "' tip='".$Parca_Tipi->get_details("tip")."'>" . $Parca_Tipi->get_details("isim") . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-row varyant-append">

                    </div>

                    <div class="form-row">
                        <div class="input-container au">
                            <label for="parca_talep_form_miktar">Miktar</label>
                            <input type="text" id="parca_talep_form_miktar" class="req posnum" name="miktar" value="1" />
                        </div>
                    </div>
                    <div class="form-row">
                        <button class="mnbtn mor">Ekle</button>
                    </div>
                </form>
            </div>
        </div>

    </script>
    <script type="text/template" data-template="personel_detay">
        <div class="popup-form">
            <div class="form">
                <div class="form-notf"></div>
                <form action="" method="POST" id="personel_detay_form">
                    <div class="form-row">
                        <div class="input-container au">
                            <label for="personel">Personel</label>
                            <select name="personel" id="personel" class="select_no_zero">
                                <option value="0">Seçiniz...</option>
                                <?php
                                foreach( $SERVIS_PERSONEL as $personel ){
                                    $Personel = new Personel( $personel["gid"] );
                                    echo "<option value='" . $Personel->get_details("gid") . "'>" . $Personel->get_details("isim") . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="input-container au">
                            <label for="is_tanimi">Yapılan İşin Tanımı</label>
                            <textarea name="is_tanimi" class="req" id="is_tanimi"></textarea>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-col">
                            <div class="input-container au">
                                <label for="baslangic">Başlangıç</label>
                                <input type="text" class="req" id="baslangic" name="baslangic" />
                            </div>
                        </div>

                        <div class="form-col">
                            <div class="input-container au">
                                <label for="bitis">Bitis</label>
                                <input type="text" class="req" id="bitis" name="bitis" />
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <button class="mnbtn mor">Ekle</button>
                    </div>
                </form>
            </div>
        </div>
    </script>


    <script type="text/javascript">

        function make_id() {
            var text = "";
            var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
            for( var i=0; i < 5; i++ )
                text += possible.charAt(Math.floor(Math.random() * possible.length));
            return text;
        }


        var FORMDATA;

        var PLAKA = null,
            CIKANLAR = {},
            GIRENLER = {},
            PERSONEL_DETAY = {};

        function stok_ekle( gc, data ){
            var durum_html = "", ico, tc, miktar_html = "";


            if( gc == "G" ){
                ico = "eksikucuk";

                //if( GIRENLER[data.stok_kodu] != undefined ) return;
                GIRENLER[data.stok_kodu] = data;

            } else {
                if( data.durum == "H"){
                    durum_html = '<span class="col-ico"><i class="dtico lethurdakucuk"></i></span>';
                    tc = "ckirmizi";
                } else {
                    durum_html = '<span class="col-ico"><i class="dtico letrevizekucuk"></i></span>';
                    tc = "cmavi";
                }
                while( CIKANLAR[data.stok_kodu] != undefined ) data.stok_kodu = make_id();
                ico = "artikucuk";

                if( CIKANLAR[data.stok_kodu] != undefined ) return;
                CIKANLAR[data.stok_kodu] = data;

            }
            if( data.miktar != undefined ) miktar_html = " ( Miktar: " + data.miktar + " )";





            $(".stok-dt").append('<li class="clearfix" data-id="'+data.stok_kodu+'">'+
                '<div class="content">'+
                '<span class="col-ico"><i class="dtico '+ico+'"></i></span>'+
                durum_html+
                '<span class="col-bigtitle light '+tc+'">'+data.parca_tipi+'</span>'+
                '<span class="col-subtitle light '+tc+'">'+data.aciklama+miktar_html+'</span>'+
            '</div>'+
            '<ul class="dtnav clearfix">'+
                '<li><button type="button" class="dtbtn dtico parca-sil carpikirmizi" btn-role="'+gc+'"></button></li>'+
                '</ul>'+
                '</li>');
        }

        function stok_cikar( gc, stok_kodu ){
            if( gc == "G" ){
                delete GIRENLER[stok_kodu];
            } else {
                delete CIKANLAR[stok_kodu];
            }
            var elem = $(".stok-dt [data-id='"+stok_kodu+"']");
            elem.fadeOut(100);
            remove_elem(elem.get(0));
        }

        function personel_ekle( data ){

            if( PERSONEL_DETAY[data.personel] != undefined ) personel_cikar( data.personel );
            PERSONEL_DETAY[data.personel] = data;

            $(".personel-dt").append('<li class="clearfix" data-id="'+data.personel+'">'+
                '<div class="content">'+
                '<span class="col-ico"><i class="dtico personelgri"></i></span>'+
                '<span class="col-bigtitle light">'+data.personel_isim+'</span>'+
            '</div>'+
            '<ul class="dtnav clearfix">'+
                '<li><button type="button" class="dtbtn dtico editmor" btn-role="personelduzenle"  ></button></li>'+
                '<li><button type="button" class="dtbtn dtico carpikirmizi" btn-role="personelissil"></button></li>'+
                '</ul>'+
                '</li>');
        }

        function personel_cikar( personel ){
            delete PERSONEL_DETAY[personel];
            remove_elem($(".personel-dt").find("[data-id='"+personel+"']").get(0));
        }
        function personel_duzenle( personel ){
            personel_popup.on();
            var form = $("#personel_detay_form");
            form.find("#personel").val( PERSONEL_DETAY[personel].personel );
            form.find("#is_tanimi").val(PERSONEL_DETAY[personel].is_tanimi);
            form.find("#baslangic").val(PERSONEL_DETAY[personel].baslangic);
            form.find("#bitis").val(PERSONEL_DETAY[personel].bitis);
        }

        $.datetimepicker.setLocale('tr');
        var dtpicker_options = {
            format:'Y-m-d H:i'
        };

        var personel_popup;
        $(document).ready(function(){
            var sections = $(".section"),
                arac_section = $(".arac-section"),
                detay_section = $(".detay-section"),
                stok_section = $(".stok-section"),
                personel_section = $(".personel-section");
            sections.hide();
            arac_section.fadeIn(300);
            //sections.fadeIn(300);



            var OTO_INPUTS = {
                PLAKA: $("#ief_plaka"),
                RUHSAT_KAPI_KODU: $("#ief_ruhsat_kapi_kodu"),
                AKTIF_KAPI_KODU: $("#ief_aktif_kapi_kodu")
            };

            OTO_INPUTS.PLAKA.focus();
            OTO_INPUTS.PLAKA.val("34 ");

            OTO_INPUTS.PLAKA.keyup(debounce(function(){
                var input = $(this).val();
                if( input.trim().length == 0 ){
                    return;
                }

                OTO_INPUTS.RUHSAT_KAPI_KODU.val("");
                OTO_INPUTS.AKTIF_KAPI_KODU.val("");

                Loader.on();
                $.ajax({
                    type: "POST",
                    url: Gitas.AJAX_URL + "otobus.php",
                    dataType: 'json',
                    data: {req: "otobus_detay", plaka: input, form_gid: true},
                    success: function (res) {
                        console.log(res);
                        if( res.ok ){
                            detay_section.fadeIn(300);
                            stok_section.fadeIn(300);
                            personel_section.fadeIn(300);

                            OTO_INPUTS.RUHSAT_KAPI_KODU.val(res.data.ruhsat_kapi_kodu);
                            OTO_INPUTS.AKTIF_KAPI_KODU.val(res.data.aktif_kapi_kodu);
                            PLAKA = input;
                            $("#form_gid").val( res.data.form_gid );

                        } else {

                            $(".stok-dt").html("");
                            $(".personel-dt").html("");
                            $("#form_gid").val( "" );
                            PERSONEL_DETAY = {};
                            GIRENLER = {};
                            CIKANLAR = {};
                            PLAKA = "";
                            detay_section.fadeOut(300);
                            stok_section.fadeOut(300);
                            personel_section.fadeOut(300);
                        }
                        Loader.off();
                    }
                });
            }, 500, false));


            personel_popup = new GPopup({baslik:"Personel Detay Ekle", content:$("script[data-template='personel_detay']").html() });
            $(".personel-ekle").click(function(){
                personel_popup.on();
                $('#baslangic').datetimepicker(dtpicker_options);
                $('#bitis').datetimepicker(dtpicker_options);
            });

            $(document).on("click", "[btn-role='personelissil']", function(){
                if( !confirm("Personel iş kaydını silmek istediğinize emin misiniz?") ) return;
                personel_cikar( $(this).parent().parent().parent().attr("data-id") );
            });

            $(document).on("click", "[btn-role='personelduzenle']", function(){
                personel_duzenle( $(this).parent().parent().parent().attr("data-id") );
            });


            $(document).on("submit", "#personel_detay_form", function(event){
                var _this = $(this);
                if( FormValidation.check(this) ){
                    var data = {
                        personel_isim: _this.find("#personel option:selected").html(),
                        personel: _this.find("#personel").val(),
                        is_tanimi: _this.find("#is_tanimi").val(),
                        baslangic: _this.find("#baslangic").val(),
                        bitis: _this.find("#bitis").val()
                    };
                    this.reset();
                    personel_ekle( data );
                    popup_form_error(_this, 1, "Personel Bilgisi Güncellendi.");
                }
                event.preventDefault();
            });

            $(document).on("click", ".cikan_parca_elle_giris", function(){
                cikan_parca_popup.set_content($("script[data-template='cikan_parca_elle_giris']").html());
            });

            $(document).on("click", ".giren_parca_barkodsuz", function(){
                giren_parca_popup.set_content($("script[data-template='giren_parca_barkodsuz']").html());
            });


            $(document).on("click", ".giren_parca_barkod", function(){
                giren_parca_popup.set_content($("script[data-template='giren_parca_barkod']").html());
                var input = $(".barkod-input");
                input.focus();
                input.keyup(debounce( function(){
                    // ajax parca kontrol
                    Loader.on();
                    var notf = input.parent().parent().find(".notf");
                    input.attr("isok", "false");
                    $.ajax({
                        type: "POST",
                        url: Gitas.AJAX_URL + "is_emri_formu.php",
                        dataType: 'json',
                        data: {req: "parca_barkod_kontrol", barkod: input.val() },
                        success: function (res) {
                            console.log(res);
                            Loader.off();
                            if( res.ok ){
                                input.attr("isok", "true");
                                notf.html('<span class="ok">Parça Bulundu</span>'+
                                            '<ul>'+
                                            '<li>Parça Tipi: <span id="barkod_parca_tipi">'+res.data.parca_tipi+'</span></li>'+
                                            '<li>Açıklama: <span id="barkod_aciklama">'+res.data.aciklama+'</span></li>'+
                                            '<li>Firma: '+res.data.firma+'</li>'+
                                            '<li>Stoğa Giriş Tarihi: '+res.data.stok_tarih+'</li>'+
                                            '<li>Revize: '+res.data.revize+'</li>'+
                                        '</ul>');


                            } else {
                                notf.html('<span class="bok">Parça Bulunamadı</span>');
                            }
                        }
                    });



                }, 500, false ));
            });

            $(document).on("click", ".giren_parca_parca_talep", function(){
                giren_parca_popup.set_content($("script[data-template='parca_talep']").html());
            });

            $(document).on("submit", "#parca_talep_form", function(event){
                var _this = $(this);
                if( FormValidation.check(this) ){
                    Loader.on();
                    //console.log(_this.serialize() +"&form_id="+$("#form_gid").val());
                    $.ajax({
                        type: "POST",
                        url: Gitas.AJAX_URL + "parca_talep.php",
                        dataType: 'json',
                        data: _this.serialize()+"&form_id="+$("#form_gid").val(),
                        success: function (res) {
                            console.log(res);
                            Loader.off();
                            if (res.ok) {
                                _this.get(0).reset();
                            } else {
                                FormValidation.show_serverside_errors( res.inputret );
                            }
                            popup_form_error(_this, res.ok, res.text);
                        }
                    });
                }
                event.preventDefault();
            });


            $(".kisayol-stok-input").keyup(debounce(function(){
                var _this = $(this),
                    sk = _this.attr("stok_kodu"),
                    val = _this.val();
                if( is_numeric(val) && val.trim() > 0 ){
                    GIRENLER[sk] = val;
                } else {
                    if( GIRENLER[sk] != undefined ) delete GIRENLER[sk];
                }
            },500, false));

            $(".kisayol-stok-cb").change( function(){
                var _this = $(this),
                    sk = _this.attr("stok_kodu");
                if( _this.is(":checked") ){
                    GIRENLER[sk] = 1;
                } else {
                    if( GIRENLER[sk] != undefined ) delete GIRENLER[sk];
                }
            });

            $(document).on("click", ".cikandurum", function(){
                var _this = $(this),
                    row = $(this.parentNode.parentNode);

                stok_cikar("C", row.attr("data-id"));
                if( _this.hasClass("lethurda") ){
                    if( row.attr("durum") != "H" ){
                        row.attr("durum", "H");
                        row.removeClass("revize");
                        row.addClass("hurda");
                        stok_ekle( "C", { parca_kontrol: 1, durum:"H", stok_kodu: row.attr("data-id"), parca_tipi: $("#parca_tipi_cikan").val(), aciklama: row.attr("aciklama") });
                    } else {
                        row.attr("durum", "");
                        row.removeClass("revize");
                        row.removeClass("hurda");

                    }
                } else{
                    if( row.attr("durum") != "R" ){
                        row.attr("durum", "R");
                        row.removeClass("hurda");
                        row.addClass("revize");
                        stok_ekle( "C", { parca_kontrol: 1, durum: "R", stok_kodu: row.attr("data-id"), parca_tipi: $("#parca_tipi_cikan").val(), aciklama: row.attr("aciklama") });
                    } else {
                        row.attr("durum", "");
                        row.removeClass("revize");
                        row.removeClass("hurda");
                    }
                }
                console.log(CIKANLAR);
            });

            $(document).on("click", '.parca-sil', function(){
                if( !confirm("Parçayı silmek istediğinizden emin misiniz?") ) return;
                var _this = $(this),
                    data_id = $(this.parentNode.parentNode.parentNode).attr("data-id");
                stok_cikar( _this.attr("btn-role"), data_id );

            });

            $(document).on("change", "#parca_tipi_cikan", function(){
                var _this = $(this),
                    val = _this.val();
                if( val == 0 ) return;
                if( _this.find(":selected").attr("tip") == "1" ){
                    // barkodlu
                    Loader.on();
                    $.ajax({
                        type: "POST",
                        url: Gitas.AJAX_URL + "is_emri_formu.php",
                        dataType: 'json',
                        data: {req: "onceki_parca_girisleri", plaka: PLAKA, parca_tipi:val},
                        success: function (res) {
                            console.log(res);
                            if( res.ok ){
                                $(".minitable-container").html( res.data );
                                $('table.minitable').DataTable();
                                $(".ui-dialog").css({"width": 700+"px"});

                                for( var stok_kodu in CIKANLAR ) {
                                    var tr = $("table.minitable [data-id='" + stok_kodu + "']");
                                    console.log(tr);
                                    if (CIKANLAR[stok_kodu].durum == "R") {
                                        tr.addClass("revize");
                                        tr.attr("durum", "R");
                                    } else if (CIKANLAR[stok_kodu].durum == "H"){
                                        tr.addClass("hurda");
                                        tr.attr("durum", "H");
                                    }
                                }

                            }
                            Loader.off();
                        }
                    });
                }
            });

            var cikan_parca_popup = new GPopup({baslik:"Çıkan Parça Ekle", content:$("script[data-template='cikan_parca_part_1']").html() });
            $(".cikan-parca-btn").click( function(){
                cikan_parca_popup.on();
            });

            var giren_parca_popup = new GPopup({baslik:"Giren Parça Ekle", content:$("script[data-template='giren_parca_part_1']").html() });
            $(".giren-parca-btn").click(function(){
                giren_parca_popup.on();
            });

            $(document).on("change", ".parca_tipi", function(){
                var val = $(".parca_tipi option:selected").val(),
                    varyant_append = $(".varyant-append");
                if( val.trim() == "0" ){
                    varyant_append.html("");
                    return;
                }
                $.ajax({
                    type: "POST",
                    url:Gitas.AJAX_URL + "parca_tipi.php",
                    dataType: 'json',
                    data: { req: "parca_tipi_select_cikis", parca_tipi: val },
                    success: function(res){
                        console.log(res);
                        varyant_append.html("");
                        var html;
                        if( res.data.tip == "1"){
                            html ="<div class='input-container au'><label for='aciklama'>Açıklama</label><input type='text' class='req' name='aciklama' id='aciklama'></div>";
                            varyant_append.append( html );
                        } else {
                            html ="<div class='input-container au'><label for='aciklama'>Açıklama</label><select class='select_no_zero' name='aciklama' id='aciklama'><option value='0'>Seçiniz..</option>";
                            for( var x = 0; x < res.data.varyantlar.length; x++ ){
                                html += "<option value='"+res.data.varyantlar[x].stok_kodu+"'>"+res.data.varyantlar[x].isim+"</option>";
                            }
                            html += "</select></div>";
                            varyant_append.append( html );
                        }
                    },
                    error: function( jqXHR, textStatus, errorThrown ){
                        console.log(textStatus);
                        console.log(errorThrown);
                    }
                });
            });

            $(".taslak").click(function(){
                if( kaydet() ){
                    $.ajax({
                        type: "POST",
                        url: Gitas.AJAX_URL + "is_emri_formu.php",
                        dataType: 'json',
                        data: FORMDATA + "&durum=2",
                        success: function (res) {
                            alert( res.text );
                            if( res.ok ){
                                location.reload();
                            } else {

                            }
                            console.log(res);
                        }
                    });
                }
            });

            $(".tamamla").click(function(){
                if( kaydet() ){
                    $.ajax({
                        type: "POST",
                        url: Gitas.AJAX_URL + "is_emri_formu.php",
                        dataType: 'json',
                        data: FORMDATA + "&durum=1",
                        success: function (res) {
                            alert( res.text );
                            if( res.ok ){
                                // yazıcı sayfasına yönlenecek
                                // formda parçlari falan yazmiyoruz o yuzden basit bi $_GET ile aliriz bunlari
                                location.reload();
                            } else {

                            }
                            console.log(res);
                        }
                    });
                }
            });



            $('#ief_gelis_tarih').datetimepicker(dtpicker_options);
            $('#ief_cikis_tarih').datetimepicker(dtpicker_options);



            $(document).on("submit", "#cikan_parca_form", function(event){
                var durum = $("[name='durum-cb']:checked"),
                    _this = $(this);
                if( FormValidation.check(this) && durum.length > 0 ){

                    var parca_kontrol, tip = _this.find(".parca_tipi option:selected").attr("tip");
                    if( tip == 1 ){
                        parca_kontrol = 2;
                    } else {
                        // barkodsuz direk çıkış stoğa eklenmeyecek
                        parca_kontrol = 1;
                    }

                    stok_ekle( "C", {   stok_kodu:make_id(),
                                        parca_kontrol: parca_kontrol,
                                        tip:  tip,
                                        parca_tipi: _this.find(".parca_tipi").val(),
                                        aciklama: _this.find("#aciklama").val(),
                                        miktar: _this.find("#miktar").val(),
                                        durum: durum.attr("durum") });
                    this.reset();
                    popup_form_error(_this, 1, "Parça Eklendi.");
                    remove_elem(_this.find("#aciklama").parent().get(0));
                }
                event.preventDefault();
            });

            $(document).on("submit", "#giren_parca_barkod_form", function(event){
                var _this = $(this), input = _this.find(".barkod-input");
                if( FormValidation.check(this) && input.attr("isok") == "true" ){
                    stok_ekle( "G", { stok_kodu:input.val(),
                        parca_tipi: _this.find("#barkod_parca_tipi").html(),
                        aciklama: _this.find("#barkod_aciklama").html() });
                    this.reset();
                    popup_form_error(_this, 1, "Parça Eklendi.");
                    remove_elem(_this.find(".notf").html(""));
                }
                event.preventDefault();
            });

            $(document).on("submit", "#giren_parca_barkodsuz_form", function(event){
                var _this = $(this);
                if( FormValidation.check(this)  ){
                    stok_ekle( "G", {   stok_kodu:make_id(),
                        parca_tipi: _this.find(".parca_tipi").val(),
                        aciklama: _this.find("#aciklama").val(),
                        miktar: _this.find("#miktar").val() });
                    this.reset();
                    remove_elem(_this.find("#aciklama").parent().get(0));
                    popup_form_error(_this, 1, "Parça Eklendi.");
                }
                event.preventDefault();
            })

        });


        function kaydet(){


            /**
             * FORM_DATA = {
                 *  form_data: serialize(form)
                 *  cikanlar: &ck=sk$mk$dr#sk$mk$dr  explode(#, ..) sonra explode( $, .. ) parcakontrol elle giriş için
                 *  girenler: &gr=sk$mk#sk$mk
                 *  personel: &ps=pk$is$ba$bi#pk$is$ba$bi
                 *
                 * }
             *
             *
             * JSOUTPUT:
             *  PERSONEL_DETAY = {
                 *      PERSONEL_GID: {
                 *          baslangic:
                 *          bitis:
                 *          is_tanimi:
                 *          personel:
                 *          personel_isim:
                 *      }
                 *  }
             *
             *  GIRENLER = {  // KISAYOL GİRİŞLER
                 *      STOK_KODU: MIKTAR
                 *  }
             *
             *  GIRENLER = {  // BARKODSUZ GİRİŞ
                 *      STOK_KODU : {
                 *          aciklama:
                            miktar:
                            stok_kodu:
                            parca_tipi
                 *      }
                 *  }
             *
             *  GIRENLER = {  // BARKODLU GİRİŞ
                 *      STOK_KODU : {
                 *          parca_tipi:
                 *          aciklama:
                 *          stok_kodu:
                 *      }
                 *  }
             *
             *  CIKANLAR = {
                 *      STOK_KODU / RANDOM:
                 *          parca_tipi:
                 *          aciklama:
                 *          stok_kodu:
                 *          miktar: // barkodsuzda
                 *          parca_kontrol:
                 *  }
             *
             * **/

            /*console.log( $("#ief").serialize() );
            console.log(GIRENLER);
            console.log(CIKANLAR);
            console.log(PLAKA);
            console.log(PERSONEL_DETAY);
            console.log($("#form_gid").val());*/

            var stok_kodu;
            var girenler_array = [], cikanlar_array = [], personel_array = [];
            FORMDATA = "";
            for( stok_kodu in GIRENLER ) {
                // [TIP][STOK_KODU][MIKTAR]
                if (GIRENLER[stok_kodu].aciklama == undefined) {
                    // kisayol giris
                    girenler_array.push("2$" + stok_kodu + "$" + GIRENLER[stok_kodu]);
                } else {
                    if (GIRENLER[stok_kodu].miktar == undefined) {
                        // barkodlu giris
                        girenler_array.push("1$" + stok_kodu + "$1");
                    } else {
                        // barkodsuz giris
                        girenler_array.push("2$" + GIRENLER[stok_kodu].aciklama + "$" + GIRENLER[stok_kodu].miktar);
                    }
                }
            }
            //console.log(girenler_array.join("#"));
            for( stok_kodu in CIKANLAR ){
                // [PARCA_KONTROL][TIP][STOK_KODU][DURUM] -> barkodlu
                // [PARCA_KONTROL][TIP][PARCA_TIPI][ACIKLAMA][MIKTAR][DURUM] -> barkodsuz
                if( CIKANLAR[stok_kodu].tip == undefined ){
                    // önceki kayıtlardan giriş ( barkodlu )
                    cikanlar_array.push( CIKANLAR[stok_kodu].parca_kontrol + "$1$" + CIKANLAR[stok_kodu].stok_kodu + "$" + CIKANLAR[stok_kodu].durum );
                } else {
                    // elle giriş ( barkodlu - barkodsuz )
                    cikanlar_array.push( CIKANLAR[stok_kodu].parca_kontrol + "$"+CIKANLAR[stok_kodu].tip+"$" + CIKANLAR[stok_kodu].parca_tipi + "$" + CIKANLAR[stok_kodu].aciklama + "$" + CIKANLAR[stok_kodu].miktar + "$" + CIKANLAR[stok_kodu].durum );
                }
            }
            //console.log(cikanlar_array.join("#"));
            for( var personel in PERSONEL_DETAY ){
                personel_array.push( personel + "$" + PERSONEL_DETAY[personel].is_tanimi + "$" + PERSONEL_DETAY[personel].baslangic + "$" + PERSONEL_DETAY[personel].bitis );
            }
            //console.log(personel_array.join("#"));
            var form = $("#ief");
            if( FormValidation.check( form.get(0) ) && Object.size( GIRENLER ) > 0 && Object.size( PERSONEL_DETAY ) > 0 && PLAKA != "" ) {
                FORMDATA = form.serialize() + "&plaka="+PLAKA+"&ck=|" + cikanlar_array.join("#") + "|&gr=|" + girenler_array.join("#") + "|&ps=|" + personel_array.join("#") + "|";
                return true;
            }
            return false;
        }


    </script>


<?php
    require 'inc/footer.php';