<?php
    require 'inc/init.php';

    $TITLE = "İş Emri Formu Oluştur";
    $AKTIVITE_KOD = Aktiviteler::IS_EMRI_FORMU_EKLEME;


    $KISAYOL_PARCALAR = array(
        "Balata" => array(
            "Sağ Ön"        => array( "stok_kodu" => "GTSPATIPBALATABScE4L1mEaczeun18", "varyant_gid" => "GTSVARYONK3Al2YWb7O74WcP" ),
            "Sol Ön"        => array( "stok_kodu" => "GTSPATIPBALATABSeBVdlvZPi9sN4a1", "varyant_gid" => "GTSVARYON1QbZKLQ4PE1QulI"),
            "Sağ Arka"      => array( "stok_kodu" => "GTSPATIPBALATABScE4L1mEaczeun18", "varyant_gid" => "GTSVARYARKAmYejSdthZ52WxAv"),
            "Sol Arka"      => array( "stok_kodu" => "GTSPATIPBALATABSeBVdlvZPi9sN4a1", "varyant_gid" => "GTSVARYARKAUramlUZWLB9WfKu"),
        ),
        "Yağ" => array(
            "Diferansiyel"  => "GTSPATIPYAGBSpmthG8MzqN5mCW7",
            "Motor"         => "GTSPATIPYAGBSl4haYynZvsfT3ZJ",
            "Şanzıman"      => "GTSPATIPYAGBSdd8UWwGRPcTZfIR",
            "Direksiyon"    => "GTSPATIPYAGBSZHD7iEvMCXCkOyB",
            "Gres"          => "GTSPATIPYAGBSudWvtF5X0wr2iBY",
            "Sıvı Gres"     => "GTSPATIPYAGBSRcSbm2lrma7PjFo"
        ),
        "Muhtelif" => array(
            "Antifriz"      => "GTSPATIPANTIFIRIZBSlIcM8vW0wEDKCm8",
            "Balata Spreyi" => "GTSPATIPBALATASPREYIBSzQHQ1HVgFl5unJN",
            "Bant"          => "GTSPATIPBANTBSYkY2wkSBzKgTr5d",
            "Silikon"       => "GTSPATIPSILIKONBSgtk7OO7GQJyyz5D"
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

    require 'inc/header.php';


?>

    <div class="ief-ozet">
        <button type="button" class="mnbtn mor" id="tamamla">TAMAMLA VE YAZDIR</button>
    </div>


    <div class="adimlar-header">
        <ul class="clearfix">
            <li ahindex="0">
                <div class="adim-ust"></div>
                <div class="adim-alt">Otobüs</div>
            </li>
            <li ahindex="1">
                <div class="adim-ust"></div>
                <div class="adim-alt">Arıza Detay</div>
            </li>
            <li ahindex="2">
                <div class="adim-ust"></div>
                <div class="adim-alt">Parça Giriş</div>
            </li>
            <li ahindex="3">
                <div class="adim-ust"></div>
                <div class="adim-alt">Parça Çıkış</div>
            </li>
            <li ahindex="4">
                <div class="adim-ust"></div>
                <div class="adim-alt">Personel Detay</div>
            </li>
        </ul>
    </div>


    <div class="adimlar">
        <div class="adim" index="0">
            <div class="plaka-container">
                <div class="plaka-input clearfix">
                    <div class="lmavi"></div>
                    <input type="text" name="plaka" id="plaka" value="34 " />
                </div>
            </div>

            <div class="gcont clearfix">
                <div class="lcont">
                    <div class="info">OTOBÜS DETAY</div>
                    <div class="ico">
                        <img src="<?php echo URL_RES_IMG ?>ico_cont_otobus.png" />
                    </div>
                </div>
                <div class="rcont">
                    <div class="info"></div>
                    <div class="rcontent">
                        <div class="input-row">
                            <div class="binput-container">
                                <label for="aktif_kapi_no">Kapı No</label>
                                <input type="text" id="aktif_kapi_no" class="kisa" />
                            </div>

                            <div class="binput-container">
                                <label for="gelis_km">Geliş KM</label>
                                <input type="text" id="gelis_km" class="kisa" />
                            </div>

                            <div class="binput-container">
                                <label for="surucu">Sürücü</label>
                                <select id="surucu" class="uzun">
                                    <option value="0">Seçiniz...</option>
                                    <?php
                                        foreach( $SURUCULER as $surucu ){
                                            echo '<option value="'.$surucu["gid"].'" >'.$surucu["isim"].'</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="input-row">
                            <div class="binput-container">
                                <label for="gelis_tarih">Geliş Tarih</label>
                                <input type="text" id="gelis_tarih" class="orta dpicker" />
                            </div>

                            <div class="binput-container">
                                <label for="cikis_tarih">Çıkış Tarih</label>
                                <input type="text" id="cikis_tarih" class="orta dpicker" />
                            </div>

                        </div>

                    </div>
                </div>
            </div>

        </div>
        <div class="adim" index="1">
            <div class="gcont clearfix">
                <div class="lcont">
                    <div class="info">ŞİKAYET</div>
                    <div class="ico">
                        <img src="<?php echo URL_RES_IMG ?>ico_cont_stok.png" />
                    </div>
                </div>
                <div class="rcont">
                    <div class="info"></div>
                    <div class="rcontent">
                        <div class="binput-container textarea">
                            <textarea class="form-notlar" id="sikayet"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="gcont clearfix">
                <div class="lcont">
                    <div class="info">ARIZA TESPİTİ VE NEDENİ</div>
                    <div class="ico">
                        <img src="<?php echo URL_RES_IMG ?>ico_cont_stok.png" />
                    </div>
                </div>
                <div class="rcont">
                    <div class="info"></div>
                    <div class="rcontent">
                        <div class="binput-container textarea">
                            <textarea class="form-notlar" id="ariza_tespit"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="gcont clearfix">
                <div class="lcont">
                    <div class="info">YAPILAN ONARIM</div>
                    <div class="ico">
                        <img src="<?php echo URL_RES_IMG ?>ico_cont_stok.png" />
                    </div>
                </div>
                <div class="rcont">
                    <div class="info"></div>
                    <div class="rcontent">
                        <div class="binput-container textarea">
                            <textarea class="form-notlar" id="yapilan_onarim"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="adim" index="2">
            <div class="gcont clearfix">
                <div class="lcont">
                    <div class="info">BALATA GİRİŞİ</div>
                    <div class="ico">
                        <img src="<?php echo URL_RES_IMG ?>ico_cont_balata.png" />
                    </div>
                </div>
                <div class="rcont">
                    <div class="info"></div>
                    <div class="rcontent">
                        <div class="input-row">
                            <div class="binput-container">
                                <div class="obareycb-container balata" varyant_gid="<?php echo $KISAYOL_PARCALAR["Balata"]["Sağ Ön"]["varyant_gid"]?>" key="<?php echo $KISAYOL_PARCALAR["Balata"]["Sağ Ön"]["stok_kodu"] ?>" value="0">
                                    <button type="button" class="obareycb"></button>
                                    <label class="obareycb-lbl" >Sağ Ön</label>
                                </div>
                            </div>

                            <div class="binput-container">
                                <div class="obareycb-container balata" varyant_gid="<?php echo $KISAYOL_PARCALAR["Balata"]["Sağ Arka"]["varyant_gid"]?>" key="<?php echo $KISAYOL_PARCALAR["Balata"]["Sağ Arka"]["stok_kodu"] ?>" value="0">
                                    <button type="button" class="obareycb"></button>
                                    <label class="obareycb-lbl" >Sağ Arka</label>
                                </div>
                            </div>

                            <div class="binput-container">
                                <div class="obareycb-container balata" varyant_gid="<?php echo $KISAYOL_PARCALAR["Balata"]["Sol Ön"]["varyant_gid"]?>" key="<?php echo $KISAYOL_PARCALAR["Balata"]["Sol Ön"]["stok_kodu"] ?>" value="0">
                                    <button type="button" class="obareycb"></button>
                                    <label class="obareycb-lbl" >Sol Ön</label>
                                </div>
                            </div>

                            <div class="binput-container">
                                <div class="obareycb-container balata" varyant_gid="<?php echo $KISAYOL_PARCALAR["Balata"]["Sol Arka"]["varyant_gid"]?>" key="<?php echo $KISAYOL_PARCALAR["Balata"]["Sol Arka"]["stok_kodu"] ?>" value="0">
                                    <button type="button" class="obareycb"></button>
                                    <label class="obareycb-lbl" >Sol Arka</label>
                                </div>
                            </div>
                        </div>
                        <div class="input-row">

                            <div class="binput-container">
                                <label>Balata Spreyi</label>
                                <input type="text" class="kisa kisayol-input" stok_kodu="<?php echo $KISAYOL_PARCALAR["Muhtelif"]["Balata Spreyi"] ?>" />
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="gcont clearfix">
                <div class="lcont">
                    <div class="info">YAĞ GİRİŞİ</div>
                    <div class="ico">
                        <img src="<?php echo URL_RES_IMG ?>ico_cont_yag.png" />
                    </div>
                </div>
                <div class="rcont">
                    <div class="info">Eğer ekleme yaptıysanız, “Ekleme” kutucuğunu işaretleyin.</div>
                    <div class="rcontent">
                        <div class="input-row">
                            <div class="binput-container ikikat">
                                <div class="katbir">
                                    <label >Diferansiyel</label>
                                    <input type="text" class="kisa kisayol-input" stok_kodu="<?php echo $KISAYOL_PARCALAR["Yağ"]["Diferansiyel"] ?>" />
                                </div>

                                <div class="katiki">
                                    <div class="obareycb-container" key="ekleme" value="0">
                                        <button type="button" class="obareycb"></button>
                                        <label class="obareycb-lbl" >Ekleme</label>
                                    </div>
                                </div>
                            </div>

                            <div class="binput-container ikikat">
                                <div class="katbir">
                                    <label>Şanzıman</label>
                                    <input type="text" class="kisa kisayol-input" stok_kodu="<?php echo $KISAYOL_PARCALAR["Yağ"]["Şanzıman"] ?>" />
                                </div>

                                <div class="katiki">
                                    <div class="obareycb-container" key="ekleme" value="0">
                                        <button type="button" class="obareycb"></button>
                                        <label class="obareycb-lbl" >Ekleme</label>
                                    </div>
                                </div>
                            </div>

                            <div class="binput-container ikikat">
                                <div class="katbir">
                                    <label>Motor</label>
                                    <input type="text" class="kisa kisayol-input" stok_kodu="<?php echo $KISAYOL_PARCALAR["Yağ"]["Motor"] ?>" />
                                </div>

                                <div class="katiki">
                                    <div class="obareycb-container" key="ekleme" value="0">
                                        <button type="button" class="obareycb"></button>
                                        <label class="obareycb-lbl" >Ekleme</label>
                                    </div>
                                </div>
                            </div>

                            <div class="binput-container ikikat">
                                <div class="katbir">
                                    <label>Direksiyon</label>
                                    <input type="text" class="kisa kisayol-input" stok_kodu="<?php echo $KISAYOL_PARCALAR["Yağ"]["Direksiyon"] ?>" />
                                </div>

                                <div class="katiki">
                                    <div class="obareycb-container" key="ekleme" value="0">
                                        <button type="button" class="obareycb"></button>
                                        <label class="obareycb-lbl" >Ekleme</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="input-row">
                            <div class="binput-container ikikat">
                                <div class="katbir">
                                    <label>Gres</label>
                                    <input type="text" class="kisa kisayol-input" stok_kodu="<?php echo $KISAYOL_PARCALAR["Yağ"]["Gres"] ?>" />
                                </div>

                                <div class="katiki">
                                    <div class="obareycb-container" key="ekleme" value="0">
                                        <button type="button" class="obareycb"></button>
                                        <label class="obareycb-lbl" >Ekleme</label>
                                    </div>
                                </div>
                            </div>

                            <div class="binput-container ikikat">
                                <div class="katbir">
                                    <label>Sıvı Gres</label>
                                    <input type="text" class="kisa kisayol-input" stok_kodu="<?php echo $KISAYOL_PARCALAR["Yağ"]["Sıvı Gres"] ?>" />
                                </div>

                                <div class="katiki">
                                    <div class="obareycb-container" key="ekleme" value="0">
                                        <button type="button" class="obareycb"></button>
                                        <label class="obareycb-lbl" >Ekleme</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="gcont clearfix">
                <div class="lcont">
                    <div class="info">MUHTELİF</div>
                    <div class="ico">
                        <img src="<?php echo URL_RES_IMG ?>ico_cont_sprey.png" />
                    </div>
                </div>
                <div class="rcont">
                    <div class="info"></div>
                    <div class="rcontent">
                        <div class="input-row">

                            <div class="binput-container">
                                <label>Antifriz</label>
                                <input type="text" class="kisa kisayol-input" stok_kodu="<?php echo $KISAYOL_PARCALAR["Muhtelif"]["Antifriz"] ?>" />
                            </div>

                            <div class="binput-container">
                                <label>Bant</label>
                                <input type="text" class="kisa kisayol-input" stok_kodu="<?php echo $KISAYOL_PARCALAR["Muhtelif"]["Bant"] ?>" />
                            </div>

                            <div class="binput-container">
                                <label>Silikon</label>
                                <input type="text" class="kisa kisayol-input" stok_kodu="<?php echo $KISAYOL_PARCALAR["Muhtelif"]["Silikon"] ?>" />
                            </div>
                        </div>
                        <div class="input-row">

                            <div class="binput-container">
                                <div class="obareycb-container ekstra-data" key="arac_yikama" value="0">
                                    <button type="button" class="obareycb"></button>
                                    <label class="obareycb-lbl" >Araç Yıkandı</label>
                                </div>
                            </div>

                            <div class="binput-container">
                                <div class="obareycb-container ekstra-data" key="kalibrasyon_yapildi" value="0">
                                    <button type="button" class="obareycb"></button>
                                    <label class="obareycb-lbl" >Kalibrasyon Yapıldı</label>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>

            <div class="gcont clearfix">
                <div class="lcont">
                    <div class="info">STOK</div>
                    <div class="ico">
                        <img src="<?php echo URL_RES_IMG ?>ico_cont_stok.png" />
                    </div>
                </div>
                <div class="rcont">
                    <div class="info"></div>
                    <div class="rcontent">
                        <div class="input-row">

                            <div class="binput-container">
                                <label for="antifriz">Parça Tipi</label>
                                <select name="" id="parca_tipi" class="uzun">
                                    <option value="0">Seçiniz...</option>
                                    <?php
                                        foreach( $PARCA_TIPLERI_GIREN_ELLE_GIRIS as $parca_tipi ){
                                            echo '<option value="'.$parca_tipi["gid"].'" tip="'.$parca_tipi["tip"].'">'.$parca_tipi["isim"].'</option>';
                                        }
                                    ?>
                                </select>
                            </div>

                        </div>

                        <div class="input-row">
                            <table class="obarey-table">
                                <thead>
                                <tr>
                                    <td>PARÇA TİPİ</td>
                                    <td>VARYANT</td>
                                    <td>AÇIKLAMA</td>
                                    <td>MİKTAR</td>
                                    <td>STOK KODU</td>
                                    <td></td>
                                </tr>
                                </thead>

                                <tbody class="parca-giris-eklenenler">

                                </tbody>

                            </table>
                        </div>


                    </div>
                </div>
            </div>
        </div>
        <div class="adim cikislar-cont" index="3">




        </div>
        <div class="adim" index="4">
            <div class="gcont clearfix">
                <div class="lcont">
                    <div class="info">PERSONEL DETAY</div>
                    <div class="ico">
                        <img src="<?php echo URL_RES_IMG ?>ico_cont_surucu.png" />
                    </div>
                </div>
                <div class="rcont">
                    <div class="info"></div>
                    <div class="rcontent">
                        <div class="input-row">
                            <button type="button" class="mnbtn mor" id="personel_ekle">PERSONEL EKLE</button>
                        </div>

                        <div class="input-row">
                            <table class="obarey-table">
                                <thead>
                                <tr>
                                    <td>PERSONEL</td>
                                    <td>İŞ TANIMI</td>
                                    <td>BAŞLANGIÇ</td>
                                    <td>BİTİŞ</td>
                                    <td></td>
                                </tr>
                                </thead>

                                <tbody class="personel-eklenenler">

                                </tbody>

                            </table>
                        </div>


                    </div>
                </div>


            </div>

        </div>
    </div>

    <div class="form-nav">
        <button type="button" class="mnbtn gri" id="form_geri">GERİ</button>
        <button type="button" class="mnbtn mor" id="form_ileri">İLERİ</button>
    </div>

    <script type="text/template" data-template="popup-barkodlu-parca">

        <div class="parca-tipi-popup-form">
            <div class="parca-tipi">Kaliper</div>
            <div class="info">Araca taktığınız parçanın barkodunu okutun.</div>

            <div class="binput-container">
                <label for="barkod">Barkod</label>
                <input type="text" id="barkod" class="uzun" ok="0" parca_tipi="0" />
            </div>

            <div class="cikis-varyant-cont">

            </div>

            <div class="parca-info">

            </div>

            <button type="button" class="mnbtn mor" id="barkodlu-tamam">TAMAM</button>

        </div>

    </script>

    <script type="text/template" data-template="popup-barkodsuz-parca">
        <div class="parca-tipi-popup-form">
            <div class="parca-tipi">Pil</div>
            <div class="info">Araca taktığınız parça ve miktarı ile ilgili bilgi verin.</div>

            <div class="input-row">
                <div class="binput-container varyant-append">

                </div>
            </div>

            <div class="input-row">
                <div class="binput-container">
                    <label for="miktar">Miktar</label>
                    <input type="text" id="miktar" class="kisa" />
                </div>
            </div>


            <button type="button" class="mnbtn mor" id="barkodsuz-tamam">TAMAM</button>
        </div>
    </script>

    <script type="text/template" data-template="popup-onceki-girisler-form">
        <div class="parca-tipi-popup-form">
            <div class="parca-tipi">Araca Önceden Takılanlar</div>
            <div class="info">Araca önceden takılmış fakat stokta kaydı olmayan parça ile ilgili verin.</div>
            <input type="hidden" id="giris_ref" value="" />
            <div class="input-row">
                <div class="binput-container">
                    <label for="aciklama">Açıklama</label>
                    <input type="text" id="aciklama" class="uzun" />
                </div>
            </div>

            <div class="input-row">
                <div class="binput-container">
                    <div class="obareycb-container" key="Durum" val="H">
                        <button type="button" class="obareycb"></button>
                        <label class="obareycb-lbl" >Hurda</label>
                    </div>
                </div>
                <div class="binput-container">
                    <div class="obareycb-container" key="Durum" val="R">
                        <button type="button" class="obareycb"></button>
                        <label class="obareycb-lbl" >Revize</label>
                    </div>
                </div>
            </div>

            <button type="button" class="mnbtn mor" id="cikis-barkodsuz-tamam">TAMAM</button>
        </div>
    </script>

    <script type="text/template" data-template="popup-onceki-girisler-table">

        <div class="parca-tipi-popup-form">
            <div class="parca-tipi">Araca Önceden Takılanlar</div>
            <div class="info">Araca önceden takılmış <span class="info-parca-tipi">Kaliper</span> parçalar listelendi. <br>Çıkan parçanın durumuna göre bilgi verin.
                <br>Eğer listede veri yoksa  <button type="button" class="mnbtn mor popup-cikis-form-btn" >Stokta Olmayan Parça Çıkışı</button> yapın.
            </div>
            <input type="hidden" id="giris_ref" value="" />
            <table class="obarey-table">
                <thead>
                    <tr>
                        <td>STOK KODU</td>
                        <td>VARYANT</td>
                        <td>AÇIKLAMA</td>
                        <td>GİRİŞ KM</td>
                        <td>GİRİŞ TARİHİ</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </thead>

                <tbody class="cikan-tbody">

                </tbody>

            </table>

        </div>

    </script>

    <script type="text/template" data-template="popup-personel-form">

        <div class="parca-tipi-popup-form">
            <div class="parca-tipi">Personel Detay</div>
            <div class="info">Servis esnasında çalışan personel ile ilgili bilgi verin.</div>
            <div class="input-row">
                <div class="binput-container">
                    <label for="personel">Personel</label>
                    <select id="personel" class="uzun">
                        <option value="0">Seçiniz...</option>
                        <?php
                            foreach( $SERVIS_PERSONEL as $personel ){
                                echo '<option value="'.$personel["gid"].'" isim="'.$personel["isim"].'">'.$personel["isim"].'</option>';
                            }
                        ?>
                    </select>
                </div>
            </div>
            <div class="input-row">
                <div class="binput-container">
                    <label for="is_tanimi">İş Tanımı</label>
                    <textarea id="is_tanimi" class="orta"></textarea>
                </div>
            </div>
            <div class="input-row">
                <div class="binput-container">
                    <label for="baslangic">Başlangıç</label>
                    <input type="text" id="baslangic" class="orta" />
                </div>
                <div class="binput-container">
                    <label for="baslangic">Bitiş</label>
                    <input type="text" id="bitis" class="orta" />
                </div>
            </div>
            <button type="button" class="mnbtn mor" id="personel-detay-tamam" >TAMAM</button>
        </div>

    </script>

    <script type="text/javascript">
        var FORMDATA;
        var FORM = {
            active_index: 0,
            ileri_btn:null,
            geri_btn:null,
            plaka_girildi: false,
            plaka: null,
            giren_parcalar: {},
            cikan_parcalar: {},
            otobus_data: {},
            form_data: {},
            personel_detay: {},
            ekstra_data: {},
            sayfa_degistir: function( adim ){
                this.active_index = adim;
                $("[ahindex]").removeClass("ok");
                $(".adim").removeClass("active");
                for( var j = 0; j < adim+1; j++ ){
                    $("[ahindex='"+j+"']").addClass("ok");
                }
                var content = $("[index='"+adim+"']");
                content.addClass("active");
                if( this.active_index == 0 ){
                    this.geri_btn.attr("disabled", true);
                } else {
                    this.geri_btn.attr("disabled", false);
                }
                if( this.active_index == 4 ){
                    this.ileri_btn.attr("disabled", true);
                } else {
                    this.ileri_btn.attr("disabled", false);
                }
            },
            init: function(){
                this.ileri_btn = $("#form_ileri");
                this.geri_btn = $("#form_geri");

                this.sayfa_degistir(0);
                this.ileri_btn.attr("disabled", true);

                var kisayol_adim = $("[index='"+2+"']");

                var inputlar = kisayol_adim.find("input"),
                    inputs = {};
                for( var j = 0; j < inputlar.length; j++ ){
                    if( inputlar[j].parentElement.className == "katbir"){
                        // altta checkbox olan inputlar
                        inputs = {
                            main: inputlar[j],
                            cb:  new Obarey_CB($(inputlar[j].parentNode.parentNode).find(".obareycb-container")[0])
                        };
                    } else {
                        inputs = {
                            main: inputlar[j]
                        };
                    }
                    // direk inputlar
                    this.giren_parcalar[ inputlar[j].getAttribute("stok_kodu") ] = new Parca({
                        data: {
                            tip: Parca_Tipi.BARKODSUZ,
                            stok_kodu: inputlar[j].getAttribute("stok_kodu"),
                            miktar: 0
                        },
                        inputs: inputs
                    });
                }

                inputlar = kisayol_adim.find(".balata");
                for( var j = 0; j < inputlar.length; j++ ){
                    this.giren_parcalar[ inputlar[j].getAttribute("varyant_gid") ] = new Parca({
                        data: {
                            tip: Parca_Tipi.BARKODSUZ,
                            stok_kodu: inputlar[j].getAttribute("key"),
                            varyant_gid: inputlar[j].getAttribute("varyant_gid"),
                            miktar: 0,
                            balata: true
                        },
                        inputs: {
                            cb:{ elem: inputlar[j] }
                        }
                    });
                }

                inputlar = kisayol_adim.find(".ekstra-data");
                for( var j = 0; j < inputlar.length; j++ ){
                    this.ekstra_data[ inputlar[j].getAttribute("key") ] = inputlar[j];
                }

                inputlar = $("[index='"+0+"']").find("input");
                for( var j = 0; j < inputlar.length; j++ ){
                    this.otobus_data[inputlar[j].id] = inputlar[j];
                }
                this.otobus_data["surucu"] = $("#surucu").get(0);

                inputlar = $("[index='"+1+"']").find("textarea");
                for( var j = 0; j < inputlar.length; j++ ){
                    this.form_data[inputlar[j].id] = inputlar[j];
                }

                /*console.log( this.otobus_data );
                console.log( this.form_data );
                console.log( this.giren_parcalar );
                console.log( this.ekstra_data );*/

                this.ileri_btn.click(function(){ FORM.sayfa_degistir(FORM.active_index+1) });
                this.geri_btn.click(function(){ FORM.sayfa_degistir(FORM.active_index+-1) });
            }
        };

        var Obarey_CB = function(elem){
          this.elem = elem;
          this.key = elem.getAttribute("key");
          this.get_val = function(){
              return elem.getAttribute("value")
          }
        };
        var Parca_Tipi = {
            BARKODLU: 1,
            BARKODSUZ: 2
        };

        var Cikan_Parca_Durum = {
            BILGI_YOK: 0,
            PARCA_YOK: 1,
            PARCA_VAR: 2
        };

        var Parca = function(options){
            this.data = options.data;
            this.inputs = options.inputs;
        };

        var Cikan_Parca = function(options){
            this.elem = options.elem;
            this.ref_id = options.ref_id;
            this.durum = options.durum;
            this.ok = options.ok;
        };

        function kaydet( t ){
            console.log(FORM);
            var uyarilar = "",
                hata = false;

            var PLAKA = FORM.otobus_data.plaka.value,
                KAPI_NO = FORM.otobus_data.aktif_kapi_no.value,
                GELIS_KM = FORM.otobus_data.gelis_km.value,
                GELIS_TARIH = FORM.otobus_data.gelis_tarih.value,
                CIKIS_TARIH = FORM.otobus_data.cikis_tarih.value,
                SURUCU = FORM.otobus_data.surucu.value,
                SIKAYET = FORM.form_data.sikayet.value,
                ARIZA_TESPIT = FORM.form_data.ariza_tespit.value,
                ONARIM = FORM.form_data.yapilan_onarim.value;

            if( PLAKA.trim() == "" ){
                uyarilar += "Otobüs plakası girin!\n";
                hata = true;
            }
            if( KAPI_NO.trim() == "" ){
                uyarilar += "Otobüs kapı kodu girin!\n";
                hata = true;
            }
            if( GELIS_KM.trim() == "" || !is_numeric(GELIS_KM.trim()) ){
                uyarilar += "Geliş KM bilgisi girin!\n";
                hata = true;
            }
            if( GELIS_TARIH.trim() == "" || !Date.parse(GELIS_TARIH) ){
                uyarilar += "Geliş tarih bilgisi girin!\n";
                hata = true;
            }
            if( CIKIS_TARIH.trim() == "" || !Date.parse(CIKIS_TARIH) ){
                uyarilar += "Çıkış tarih bilgisi girin!\n";
                hata = true;
            }
            if( SURUCU == "0" ){
                uyarilar += "Sürücü bilgisi girin!\n";
                hata = true;
            }



            var t1 = new Date( GELIS_TARIH ),
                t2 = new Date( CIKIS_TARIH );

            if( t1 >= t2 ){
                alert("Geliş tarihi çıkış tarihinden önce olmalıdır!");
                return;
            }

            if( SIKAYET.trim() == "" ){
                uyarilar += "Arıza şikayet bilgisi girin!\n";
                hata = true;
            }
            if( ARIZA_TESPIT.trim() == "" ){
                uyarilar += "Arıza tespit bilgisi girin!\n";
                hata = true;
            }
            if( ONARIM.trim() == "" ){
                uyarilar += "Arıza onarım bilgisi girin!\n";
                hata = true;
            }


            if( Object.size(FORM.personel_detay) == 0 ) {
                uyarilar += "Personel bilgisi verilmedi!\n";
                hata = true;
            }


            var GIRENLER = {}, CIKANLAR = {}, item;
            for( var sk in FORM.giren_parcalar ){
                item = FORM.giren_parcalar[sk];
                if( item.data.tip == Parca_Tipi.BARKODLU ){
                    GIRENLER[sk] = item.data;
                } else {
                    if( item.inputs.main == undefined ) {
                        if( item.inputs.cb == undefined ){
                            // barkodsuz kisayol olmayan girisler
                            GIRENLER[sk] = item.data;
                        } else {
                            // sadece cb olan
                            if ($(item.inputs.cb.elem).hasClass("selected")) {
                                item.data["miktar"] = 1;
                                if( item.data.balata != undefined ){
                                    GIRENLER[item.data.varyant_gid] = item.data;
                                } else {
                                    GIRENLER[sk] = item.data;
                                }


                            } else {
                                item.data["miktar"] = 0;
                            }
                        }
                    } else {
                        // inputlu girişler
                        if( item.data.miktar > 0 ){
                            if( item.inputs.cb != undefined ){
                                if ($(item.inputs.cb.elem).hasClass("selected")) {
                                    item.data["ekleme"] = "true";
                                } else {
                                    delete item.data["ekleme"];
                                }
                            }
                            GIRENLER[sk] = item.data;
                        }
                    }
                }
            }



            for( var sk in FORM.cikan_parcalar ){
                item = FORM.cikan_parcalar[sk];
                if( item.ok == Cikan_Parca_Durum.BILGI_YOK ){
                    uyarilar += "Çıkan parçalar kısmında eksik var! Tüm girdiğiniz parçaların çıkışını yaptığınıza emin olun!\n";
                    hata = true;
                } else {
                    if( item.ok == Cikan_Parca_Durum.PARCA_YOK ){
                        CIKANLAR[sk] = {
                            ref: sk,
                            parca_yok: true
                        }
                    } else {
                        if( item.stok_kodu != "YOK" ){
                            // stokta olmayan parca
                            CIKANLAR[sk] = {
                                ref: item.ref,
                                stok_kodu: item.stok_kodu,
                                durum: item.durum,
                                aciklama: item.aciklama,
                                varyant_gid: item.varyant_gid
                            }
                        } else {
                            // stoktaki parça
                            CIKANLAR[sk] = {
                                ref: item.ref,
                                stok_kodu: item.stok_kodu,
                                durum: item.durum,
                                varyant_gid: item.varyant_gid
                            }
                        }
                    }
                }
            }

            if( Object.size(GIRENLER) == 0 ){
                uyarilar += "Giren parça bilgisi yok!\n";
                hata = true;
            }

            if( hata ){
                alert(uyarilar);
                return false;
            } else {
                console.log(FORM);
                console.log(CIKANLAR);
                console.log(GIRENLER);
                console.log("OK");

                var output = {
                    plaka: PLAKA,
                    aktif_kapi_no: KAPI_NO,
                    surucu: SURUCU,
                    gelis_km: GELIS_KM,
                    gelis_tarih: GELIS_TARIH,
                    cikis_tarih: CIKIS_TARIH,
                    sikayet: SIKAYET,
                    ariza_tespit: ARIZA_TESPIT,
                    onarim: ONARIM,
                    girenler: GIRENLER,
                    cikanlar: CIKANLAR,
                    personel_detay: FORM.personel_detay
                };

                output["durum"] = t;


                if( $(FORM.ekstra_data.arac_yikama).hasClass("selected") ) output["arac_yikama"] = true;
                if( $(FORM.ekstra_data.kalibrasyon_yapildi).hasClass("selected") ) output["kalibrasyon_yapildi"] = true;

                FORMDATA = new FormData();
                FORMDATA.append("FORMDATA", JSON.stringify(output) );
                FORMDATA.append("req", "is_emri_formu_ekle" );
                console.log(output);

                Loader.on();
                $.ajax({
                    type: "POST",
                    url: Gitas.AJAX_URL + "is_emri_formu.php",
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    data: FORMDATA,
                    success: function (res) {
                        console.log(res);
                        Loader.off();
                        if (!res.ok) {
                            alert(res.text);
                        }

                    }
                });

                return true;
            }


        }



        $(document).ready(function(){
            FORM.init();

            $("#tamamla").click(function(){
                if( kaydet(1) ){
                    if( confirm( "İş Emri Formu kaydedildi. Yazdırma sayfasına gitmek istiyor musunuz?") ){
                        //location.reload();
                    } else {
                        //location.reload();
                    }
                }
            });


            $("#plaka").keyup(debounce(function(){
                if( FORM.plaka_girildi ){
                    if( !confirm("Eğer plakayı değiştirirseniz parça giriş - çıkışları sıfırlanacaktır. Emin misiniz?") ){
                        $(this).val( FORM.plaka );
                        return;
                    }
                }

                var plaka = $(this).val();
                if( plaka.trim() != "" ){
                    Loader.on();
                    $.ajax({
                        type: "POST",
                        url: Gitas.AJAX_URL + "otobus.php",
                        dataType: 'json',
                        data: {req: "otobus_detay", plaka: plaka, form_gid: true},
                        success: function (res) {
                            console.log(res);
                            if( res.ok ){
                                FORM.plaka_girildi = true;
                                FORM.ileri_btn.attr("disabled", false);
                                FORM.plaka = plaka;
                            } else {

                                FORM.plaka_girildi = false;
                                FORM.plaka = null;
                                FORM.ileri_btn.attr("disabled", true);
                            }
                            Loader.off();
                        }
                    });
                }
            }, 500, false));

            $("#parca_tipi").change(function(){
               var _this = $(this),
                   _opt = _this.find("option:selected");
               if( _this.val() != 0 ){
                   if( _opt.attr("tip") == Parca_Tipi.BARKODLU ){
                       Popup.on( $("script[data-template='popup-barkodlu-parca']").html(), "Parça Girişi" );
                       $("#barkod").focus().attr("parca_tipi", _opt.html());

                   } else if( _opt.attr("tip") == Parca_Tipi.BARKODSUZ ){
                       Popup.on( $("script[data-template='popup-barkodsuz-parca']").html(), "Parça Girişi" );
                       var varyant_append = $(".varyant-append");
                       if( _this.val().trim() == "0" ){
                           varyant_append.html("");
                           return;
                       }
                       GitasREQ.barkodsuz_varyant_stok_kodu_listele( _this.val(), function(res){
                           console.log(res);
                           varyant_append.html("");
                           if( res.data.varyantlar.length == 0 ) return;
                           var html;
                           html ="<div class='binput-container'><label for='varyant_gid'>Varyant</label><select class='select_no_zero uzun' name='varyant_gid' id='varyant_gid'><option value='0'>Seçiniz..</option>";
                           for( var x = 0; x < res.data.varyantlar.length; x++ ){
                               html += "<option value='"+res.data.varyantlar[x].gid+"'>"+res.data.varyantlar[x].isim+"</option>";
                           }
                           html += "</select><input type='hidden' id='patip_val' value='"+_opt.html()+"' /></div>";
                           varyant_append.append( html );
                       });
                   }
                   $(".parca-tipi").html( _opt.html() );
               }
            });

            $(document).on("keyup", "#barkod", debounce(function(){
                Loader.on();
                var _this = $(this),
                    notf = _this.parent().parent().find(".parca-info");

                _this.attr("ok", "0");
                var cikis_varyantlar_input = $(".cikis-varyant-cont");
                $.ajax({
                    type: "POST",
                    url: Gitas.AJAX_URL + "is_emri_formu.php",
                    dataType: 'json',
                    data: {req: "parca_barkod_kontrol", barkod: this.value, parca_tipi:_this.attr("parca_tipi") },
                    success: function (res) {
                        console.log(res);
                        Loader.off();
                        cikis_varyantlar_input.html("");
                        if( res.ok ){
                            if( res.data.cikis_varyantlar != undefined ){
                                _this.attr("cikis_varyant_var", "true");
                                var cvaryant = '<div class="binput-container">'+
                                                    '<label for="cikis_varyant">Varyant</label><select id="cikis_varyant" name="cikis_varyant" class="select_no_zero"><option value="0">Seçiniz...</option>';
                                for( var j = 0; j < res.data.cikis_varyantlar.length; j++ ){
                                    cvaryant += '<option keyval="'+res.data.cikis_varyantlar[j]["parent"]+' - '+res.data.cikis_varyantlar[j]["isim"]+'" value="'+res.data.cikis_varyantlar[j]["gid"]+'">'+res.data.cikis_varyantlar[j]["parent"]+' - '+res.data.cikis_varyantlar[j]["isim"]+'</option>';
                                }
                                cikis_varyantlar_input.html( cvaryant + "</select></div>" );
                            }
                            _this.attr("ok", "1")
                                .attr("varyant", res.data.parca.varyant)
                                .attr("aciklama", res.data.parca.aciklama)
                                .attr("firma", res.data.parca.firma)
                                .attr("stoga_giris_tarihi", res.data.parca.tarih);

                            notf.html('<div class="state ok">Parça Bulundu!</div>'+
                                '<ul>'+
                                '<li>Parça Tipi: <span id="barkod_parca_tipi">'+res.data.parca.parca_tipi+'</span></li>'+
                                '<li>Varyant: <span id="barkod_varyant">'+res.data.parca.varyant+'</span></li>'+
                                '<li>Açıklama: <span id="barkod_aciklama">'+res.data.parca.aciklama+'</span></li>'+
                                '<li>Firma: '+res.data.parca.firma+'</li>'+
                                '<li>Stoğa Giriş Tarihi: '+res.data.parca.tarih+'</li>'+
                                '</ul>');


                        } else {
                            notf.html('<div class="state bok">Parça Bulunamadı!</div>');
                        }
                    }
                });

            }, 500, false));

            $(".kisayol-input").keyup(function(){
                var _this = $(this),
                    _val = _this.val(),
                    _sk = _this.attr("stok_kodu");

                if( _val.trim() == "" ){
                    FORM.giren_parcalar[_sk].data.miktar = 0;
                } else {
                    if( is_numeric(_val) ){
                        FORM.giren_parcalar[_sk].data.miktar = parseInt(_val);
                    } else{
                        FORM.giren_parcalar[_sk].data.miktar = 0;
                        _this.val("0");
                        alert("Lütfen numerik giriş yapınız!");
                    }
                }
            });

            $(document).on("click", ".obareycb-container", function(){
                $(this).toggleClass("selected");
                ( this.getAttribute("value") == "1" ) ? this.setAttribute("value", "0" ) : this.setAttribute("value", "1" );
            });

            $(document).on("click", "#barkodlu-tamam", function(){
                var barkod = $("#barkod"),
                    cikis_varyant = $("#cikis_varyant"),
                    val = barkod.val(),
                    parca_tipi  = barkod.attr("parca_tipi"),
                    varyant = barkod.attr("varyant"),
                    varyant_gid = "YOK";
                if( barkod.attr("ok") == "1" ){
                    if( FORM.giren_parcalar[val] != undefined ){
                        alert("Bu parça zaten girildi!");
                    } else {
                        var parca_data = {
                            tip: Parca_Tipi.BARKODLU,
                            stok_kodu: val,
                            miktar: 1
                        };
                        if( cikis_varyant.length == 1 && cikis_varyant.val() == "0"){
                            alert("Çıkış varyantı seçimini yapınız!");
                            return;
                        }
                        if( barkod.attr("cikis_varyant_var") != undefined ){

                            parca_data["varyant_gid"] = cikis_varyant.val();
                            varyant_gid = parca_data["varyant_gid"];
                            varyant = cikis_varyant.find("option:selected").attr("keyval");
                        }
                        FORM.giren_parcalar[val] = new Parca({
                            data: parca_data,
                            inputs: {}
                        });
                        var cikislar_cont = $(".cikislar-cont"),
                            parca_kutu = cikislar_cont.find("."+parca_tipi);
                        var kutu_html = '<div class="parca-cikis-cont bok" stok_kodu="'+val+'">'+
                                            '<div class="title">'+parca_tipi+ ' ( V:' + varyant +' )</div>'+
                                            '<div class="state">Bilgi verilmedi.</div>'+
                                            '<div class="buton-cont"><button type="button" varyant_gid="'+varyant_gid+'" parca_tipi="'+parca_tipi+'" class="mnbtn mor onceki-girisler-btn">ÖNCEKİ GİRİŞLER</button></div>'+
                                            '<div class="obareycb-container parca-cikmadi" >'+
                                            '<button type="button" class="obareycb"></button>'+
                                            '<label class="obareycb-lbl" >Parça Çıkmadı</label>'+
                                            '</div>'+
                                        '</div>';
                        if( parca_kutu.length == 0 ) {
                            // ilk parça tipi
                            cikislar_cont.append('<div class="gcont clearfix '+parca_tipi+'">'+
                                '<div class="lcont">'+
                                '<div class="info">'+parca_tipi+'</div>'+
                                '<div class="ico">'+
                                '<img src="<?php echo URL_RES_IMG ?>ico_cont_stok.png" />'+
                                '</div>'+
                                '</div>'+
                                '<div class="rcont">'+
                                '<div class="info">Araca "'+parca_tipi+'" girişi yaptınız. Çıkan parça(lar) hakkında bilgi verin.</div>'+
                                '<div class="rcontent">'+
                                    kutu_html+
                                '</div>'+
                                '</div>'+
                                '</div>');
                        } else {
                            // cont var sadece kutucugu eklicez
                            parca_kutu.find(".rcontent").append( kutu_html );
                        }

                        FORM.cikan_parcalar[val] = new Cikan_Parca({
                            elem: $(".cikislar-cont [stok_kodu='"+val+"']"),
                            ok: Cikan_Parca_Durum.BILGI_YOK,
                            ref_id: val
                        });
                        $(".parca-giris-eklenenler").append('<tr>'+
                                '<td>'+parca_tipi+'</td>'+
                                '<td>'+varyant+'</td>'+
                                '<td>'+barkod.attr("aciklama")+'</td>'+
                                '<td>1</td>'+
                                '<td>'+val.substr(0, 25)+'...</td>'+
                                '<td><button type="button" stok_kodu="'+val+'" class="dtbtn dtico carpikirmizi giren-parca-sil"></button></td>'+
                            '</tr>');
                        Popup.off();
                        $("#parca_tipi").val("0");
                    }
                } else {
                    alert("Parça bulunamadı!");
                }
            });

            $(document).on("click", "#barkodsuz-tamam", function(){
                var varyant_elem = $("#varyant_gid"),
                    aciklama = varyant_elem.val(),
                    miktar = $("#miktar").val(),
                    opt = varyant_elem.find("option:selected");
                if( aciklama == "0" || miktar.trim() == "" || !FormValidation.posnum(miktar)){
                    alert("Formda eksiklikler var!");
                } else {
                    if( FORM.giren_parcalar[aciklama] != undefined ){
                        // zaten eklenmişse adet arttiriyoruz
                        var yeni_miktar = parseInt(FORM.giren_parcalar[aciklama].data.miktar) + parseInt(miktar);
                        FORM.giren_parcalar[aciklama].data.miktar = yeni_miktar;
                        $(".parca-giris-eklenenler").find("[stok_kodu='"+aciklama+"']").parent().parent().find(".miktar").html( yeni_miktar );
                    } else {
                        FORM.giren_parcalar[aciklama] = new Parca({
                            data: {
                                tip: Parca_Tipi.BARKODSUZ,
                                stok_kodu: aciklama,
                                miktar: miktar
                            },
                            inputs: {}
                        });
                        $(".parca-giris-eklenenler").append('<tr>'+
                            '<td>'+$("#patip_val").val()+'</td>'+
                            '<td>'+opt.html()+'</td>'+
                            '<td></td>'+
                            '<td class="miktar">'+miktar+'</td>'+
                            '<td>'+aciklama.substr(0, 25)+'...</td>'+

                            '<td><button type="button" stok_kodu="'+aciklama+'" class="dtbtn dtico carpikirmizi giren-parca-sil"></button></td>'+
                            '</tr>');
                    }
                    Popup.off();
                    $("#parca_tipi").val("0");
                }
            });

            $(document).on("click", ".onceki-girisler-btn", function(){
                var _this = $(this);
                Popup.on($("script[data-template='popup-onceki-girisler-table']").html(), "Parça Çıkışı" );

                var _pt = _this.attr("parca_tipi"),
                    _varyant_gid = _this.attr("varyant_gid"),
                    _ref = _this.parent().parent().attr("stok_kodu");
                $(".info-parca-tipi").html(_pt);
                $(".popup-cikis-form-btn").attr("parca_tipi", _pt);
                $("#giris_ref").val(_ref).attr("varyant_gid", _varyant_gid );

                Loader.on();
                $.ajax({
                    type: "POST",
                    url: Gitas.AJAX_URL + "is_emri_formu.php",
                    dataType: 'json',
                    data: {req: "onceki_parca_girisleri", plaka: FORM.otobus_data.plaka.value, parca_tipi: _pt, varyant_gid:_varyant_gid },
                    success: function (res) {
                        console.log(res);
                        if (res.ok) {
                            var cikan_body = $(".cikan-tbody");
                            for( var j = 0; j < res.data.length; j++ ){
                                // zaten eklenmis parcalari listelemiyoruz
                                if( FORM.cikan_parcalar[_ref].stok_kodu == res.data[j].stok_kodu ) continue;
                                cikan_body.append('<tr data-id="'+res.data[j].stok_kodu+'">'+
                                    '<td title="'+res.data[j].stok_kodu+'" >'+res.data[j].stok_kodu.substr(0, 25)+'...</td>'+
                                    '<td>'+res.data[j].varyant+'</td>'+
                                    '<td>'+res.data[j].aciklama+'</td>'+
                                    '<td>'+res.data[j].km+'</td>'+
                                    '<td>'+res.data[j].tarih+'</td>'+
                                    '<td class="icotd"><button type="button" title="Revize" class="mtbtn minitableico cikandurum letrevize"></button></td>'+
                                    '<td class="icotd"><button type="button" title="Hurda" class="mtbtn minitableico cikandurum lethurda"></button></td>'+
                                    '<td class="icotd"><button type="button" title="Parça Çıkmadı" class="mtbtn minitableico cikandurum letyok"></button></td>'+
                                    '</tr>');
                            }


                        }
                        Loader.off();
                    }
                });

            });

            $("#personel_ekle").click(function(){
                Popup.on($("script[data-template='popup-personel-form']").html(), "Personel Detay" );
                for( var pid in FORM.personel_detay ){
                    remove_elem($("#personel").find("option[value='"+pid+"']").get(0));
                }
                $("#baslangic").datetimepicker(dtpicker_options);
                $("#bitis").datetimepicker(dtpicker_options);
            });

            $(document).on("click", ".personel-sil", function(){
                if( confirm("Personel bilgisini silmek istediğinizden emin misiniz?") ){
                    var _pid = $(this).parent().parent().attr("data-id");
                    delete FORM.personel_detay[_pid];
                    remove_elem($(".personel-eklenenler").find("[data-id='"+_pid+"']").get(0));
                }
            });


            $(document).on("click", "#personel-detay-tamam", function(){

                var _personel_elem = $("#personel"),
                    _personel   = _personel_elem.val(),
                    _istanimi   = $("#is_tanimi").val(),
                    _baslangic  = $("#baslangic").val(),
                    _bitis      = $("#bitis").val();

                var t1 = new Date( _baslangic ),
                    t2 = new Date( _bitis );

                if( t1 >= t2 ){
                    alert("Başlangıç tarihi bitişten önce olmalıdır!");
                    return;
                }

                if( _personel == "0" || _istanimi.trim() == "" || _baslangic.trim() == "" || _bitis.trim() == "" ){
                    alert("Formda eksikliklikler var!");
                } else {
                    FORM.personel_detay[_personel] = {
                        "personel": _personel,
                        "personel_isim": _personel_elem.find("option:selected").attr("isim"),
                        "is_tanimi": _istanimi,
                        "baslama": _baslangic,
                        "bitis": _bitis
                    };

                    $(".personel-eklenenler").append('<tr data-id="'+_personel+'">'+
                        '<td>'+ _personel_elem.find("option:selected").attr("isim")+'</td>'+
                        '<td>'+_istanimi.trim(0,30)+'...</td>'+
                        '<td>'+_baslangic+'</td>'+
                        '<td>'+_bitis+'</td>'+
                        '<td><button type="button" class="dtbtn dtico carpikirmizi personel-sil"></button></td>'+
                        '</tr>');

                    Popup.off();
                }
            });

            $(document).on("click", ".personel-sil", function(){

            });

            $(document).on("click", ".giren-parca-sil", function(){
                if( confirm("Girilen parçayı silmek istediğinize emin misiniz?") ){
                    var _this = $(this),
                        _sk = _this.attr("stok_kodu");
                    delete FORM.giren_parcalar[_sk];
                    delete FORM.cikan_parcalar[_sk];
                    // girilenler tablosundan sil
                    remove_elem( _this.parent().parent().get(0) );
                    // cikislar tablosundan sil
                    var cikislar_cont = $(".cikislar-cont"),
                        elem = cikislar_cont.find("[stok_kodu='"+_sk+"']");
                    if( elem.parent().find(".parca-cikis-cont").length == 1 ){
                        // eger son parca tipiyse gcont u sil
                        remove_elem(elem.parent().parent().parent().get(0));
                    }
                    // en son elem i siliyoruz
                    remove_elem( elem.get(0) );
                }
            });

            $(document).on("click", ".popup-cikis-form-btn", function(){
                var _this = $(this);
                Popup.on($("script[data-template='popup-onceki-girisler-form']").html(), "Parça Girişi" );
                var giris_ref = _this.parent().parent().find("#giris_ref");
                $("#giris_ref").val(giris_ref.val()).attr("varyant_gid", giris_ref.attr("varyant_gid"));
            });

            $(document).on("click", "#cikis-barkodsuz-tamam", function(){

                var _aciklama = $("#aciklama").val(),
                    _durum = $(this).parent().find(".selected"),
                    giris_ref_elem = $("#giris_ref"),
                    _ref = giris_ref_elem.val(),
                    _varyant_gid = giris_ref_elem.attr("varyant_gid");
                if( _aciklama.trim() == "" || _durum.length == 0 ){
                    alert("Formda eksiklikler var!");
                } else {

                    $(".cikislar-cont").find("[stok_kodu='"+_ref+"']").find(".obareycb-container").removeClass("selected").attr("value", 0);;

                    FORM.cikan_parcalar[_ref].durum = _durum.attr("val");
                    FORM.cikan_parcalar[_ref].stok_kodu = "YOK"; // server side da YOK gorunce stoğa eklicez
                    FORM.cikan_parcalar[_ref].varyant_gid = _varyant_gid;
                    FORM.cikan_parcalar[_ref].aciklama = _aciklama;
                    FORM.cikan_parcalar[_ref].ref = _ref;
                    FORM.cikan_parcalar[_ref].ok = Cikan_Parca_Durum.PARCA_VAR;
                    FORM.cikan_parcalar[_ref].elem.removeClass("bok");
                    FORM.cikan_parcalar[_ref].elem.addClass("ok");
                    FORM.cikan_parcalar[_ref].elem.find(".state").html("Kayıtsız parça"+" ("+_durum.attr("val")+")");
                    Popup.off();

                }


            });

            $.datetimepicker.setLocale('tr');
            var dtpicker_options = {
                format:'Y-m-d H:i'
            };
            var dtpicker_options_dt = {
                format:'Y-m-d'
            };
            $(".dpicker").datetimepicker(dtpicker_options);

            $(document).on("click", "[key='Durum']", function(){
                var _this = $(this);
                var radios = _this.parent().parent().find(".selected");
                console.log(radios);
                if( radios.length > 0 ){
                    radios.removeClass("selected");
                }
                _this.addClass("selected");

            });

            $(document).on("click", ".cikandurum", function(){
                var _this = $(this),
                    _ref = $("#giris_ref").val(), // giren parcanin stok kodu
                    _sk = _this.parent().parent().attr("data-id"), // cikan parcanin stok kodu
                    _durum;
                if( _this.hasClass("letrevize") ){
                    _durum = "R";
                } else if( _this.hasClass("lethurda") ){
                    _durum = "H";
                } else if( _this.hasClass("letyok") ){
                    _durum = "Y";
                }
                $(".cikislar-cont").find("[stok_kodu='"+_ref+"']").find(".obareycb-container").removeClass("selected").attr("value", 0);
                FORM.cikan_parcalar[_ref].durum = _durum;
                FORM.cikan_parcalar[_ref].ref = _ref;
                FORM.cikan_parcalar[_ref].stok_kodu = _sk; // cikan parcanin stok kodunu yapistir
                FORM.cikan_parcalar[_ref].ok = Cikan_Parca_Durum.PARCA_VAR;
                FORM.cikan_parcalar[_ref].elem.removeClass("bok");
                FORM.cikan_parcalar[_ref].elem.addClass("ok");
                FORM.cikan_parcalar[_ref].elem.find(".state").html(_sk.substr(0,25)+" ("+_durum+")");
                Popup.off();
            });


            $(document).on("click", ".parca-cikmadi", function(){
                var _this = $(this),
                    _ref = _this.parent().attr("stok_kodu");
                if( _this.attr("value") == "1" ){
                    delete FORM.cikan_parcalar[_ref].durum;
                    delete FORM.cikan_parcalar[_ref].stok_kodu;
                    FORM.cikan_parcalar[_ref].ok = Cikan_Parca_Durum.PARCA_YOK;
                    FORM.cikan_parcalar[_ref].elem.removeClass("bok");
                    FORM.cikan_parcalar[_ref].elem.addClass("ok");
                    FORM.cikan_parcalar[_ref].elem.find(".state").html("Parça Çıkmadı!");
                } else {
                    FORM.cikan_parcalar[_ref].ok = Cikan_Parca_Durum.BILGI_YOK;
                    FORM.cikan_parcalar[_ref].elem.addClass("bok");
                    FORM.cikan_parcalar[_ref].elem.find(".state").html("Bilgi yok!");
                }

            });


        });


    </script>




<?php

    require 'inc/footer.php';
