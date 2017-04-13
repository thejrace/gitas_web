<?php
    /**
     * Created by PhpStorm.
     * User: Jeppe
     * Date: 06.03.2017
     * Time: 12:07
     */

    require 'inc/init.php';

    // [klasorun koyulacagi klasör]> git clone [git url]

    // ---- dosya upload ---
    // git add [dosya_adi] / git add . ( tum dosyalari al )
    // git commit -m "Obarey beybe"
    // git push

    // git pull ( repten degisiklikleri guncelle )
    // git status ( guncellik kontrolu )

    var_dump( Session::get("suruculer_test") );

    class Gitas_Test {

        public function gitas_hash_test(){

            echo Gitas_Hash::hash_olustur( Gitas_Hash::$PARCA_TIPI, array( "isim" => "Balata" ) );
            echo '<br>';

            echo Gitas_Hash::hash_olustur( Gitas_Hash::$BARKODLU_PARCA, array( "parca_tipi" => "GTSPATIPKALIPER" ) );
            echo '<br>';

            echo Gitas_Hash::hash_olustur( Gitas_Hash::$BARKODSUZ_PARCA, array( "isim"  => "Sağ Ön", "parca_tipi" => "GTSPATIPKALIPER" ) );
            echo '<br>';

            echo Gitas_Hash::hash_olustur( Gitas_Hash::$PERSONEL, array( "isim"  => "Veli Konstantin", "seviye" => Personel::$SURUCU ) );
            echo '<br>';

            echo Gitas_Hash::hash_olustur( Gitas_Hash::$IS_EMRI_FORMU, array( "plaka" => "34 YG 5848") );
            echo '<br>';
        }

        public function parca_tipi_test(){


            /************  PARÇA TİPİ EKLE BARKODSUZ ***********/

            $parca_tipi_barkodsuz_input = array(
                "isim"                       => "Balata",
                "tip"                        => Parca_Tipi::$BARKODSUZ,
                "kategori"                   => Parca_Tipi::$MEKANIK,
                "miktar_olcu_birimi"         => Parca_Tipi::$ADET,
                "ideal_degisim_sikligi_alt"  => 0,
                "ideal_degisim_sikligi_ust"  => 0,
                "kritik_seviye_limiti"       => 0
            );

            $parca_tipi_barkodsuz_varyantlar_input = array(
                array( "isim" => "Sağ" ),
                array( "isim" => "Sol" )
            );

            if( $parca_tipi_barkodsuz_input["tip"] == Parca_Tipi::$BARKODSUZ ) {
                $Parca_Tipi_Test_0 = new Parca_Tipi();
                $Parca_Tipi_Test_0->ekle( $parca_tipi_barkodsuz_input );
                if( $Parca_Tipi_Test_0->is_ok() ){
                    // varyantlari barkodsuz parca olarak ekliyoruz
                    foreach( $parca_tipi_barkodsuz_varyantlar_input as $varyant ){
                        $Barkodsuz_Parca = new Barkodsuz_Parca();
                        $Barkodsuz_Parca->ekle(array(
                            "isim"   => $varyant["isim"],
                            "miktar" => 0,
                            "tip"    => $Parca_Tipi_Test_0->get_details("gid")
                        ));
                        echo $Barkodsuz_Parca->get_return_text() . "<br>";
                    }
                }
                echo $Parca_Tipi_Test_0->get_return_text();
            }


            echo '<br>';
            /************  PARÇA TİPİ EKLE BARKODLU  ***********/

            $parca_tipi_barkodlu_input = array(
                "isim"                       => "Far",
                "tip"                        => Parca_Tipi::$BARKODLU,
                "kategori"                   => Parca_Tipi::$MEKANIK,
                "miktar_olcu_birimi"         => Parca_Tipi::$ADET,
                "ideal_degisim_sikligi_alt"  => 0,
                "ideal_degisim_sikligi_ust"  => 0,
                "kritik_seviye_limiti"       => 0
            );

            if( $parca_tipi_barkodlu_input["tip"] == Parca_Tipi::$BARKODLU ) {
                $Parca_Tipi_Test_1 = new Parca_Tipi();
                $Parca_Tipi_Test_1->ekle($parca_tipi_barkodlu_input);
                echo $Parca_Tipi_Test_1->get_return_text();
            }

            /************  PARÇA TİPİ NULL PARAMETRE ***********/



            /************  PARÇA TİPİ PARAMETRELI DUZENLEME ***********/
            /*$Parca_Tipi_Test_2 = new Parca_Tipi( 2 );
            echo 'Parca Tipi ID Parametre : ';
            var_dump ($Parca_Tipi_Test_2->exists());

            echo '<br>';
            if( $Parca_Tipi_Test_2->exists() ){
                $Parca_Tipi_Test_2->duzenle( array(
                    "ideal_degisim_sikligi"  => 4000,
                    "kritik_seviye_limiti"   => 15
                ));
            }
            //if( $Parca_Tipi_Test_1->is_ok() )
            echo $Parca_Tipi_Test_2->get_return_text();
            echo '<br>';*/

            /************  PARÇA TİPİ STOK KODU PARAMETRELI ***********/
            /*$Parca_Tipi_Test_3 = new Parca_Tipi( "GTSPATIPBSMEKBLT" );
            echo 'Parca Tipi GID Parametre : ';
            var_dump ($Parca_Tipi_Test_3->exists());
            echo '<br>';*/
        }

        public function parca_test(){

            $Barkodlu_Parca_Tipi  = new Parca_Tipi("Kaliper");
            $Barkodsuz_Parca_Tipi = new Parca_Tipi("Balata");

            /************  BARKODLU PARÇA EKLEME ***********/
            $Barkodlu_Parca = new Barkodlu_Parca();
            $Barkodlu_Parca->ekle( array(
                "aciklama"          => "MAN 12V21",
                "tip"               => $Barkodlu_Parca_Tipi->get_details("gid"),
                "fatura_no"         => 20,
                "satici_firma"      => "Test Firma",
                "garanti_suresi"    => Common::get_current_date(),
                "parca_giris_id"    => "TEST"
            ));
            echo $Barkodlu_Parca->get_return_text();
            echo '<br>';

            /************  BARKODLU PARÇA DÜZENLEME ***********/
            $Barkodlu_Parca = new Barkodlu_Parca( "GTSPATIPKALIPERBLuFMprVPyYZoZOcVO9onBIVfsKDuYWRygwssTdLCX" );
            if( $Barkodlu_Parca->exists() ){
                $Barkodlu_Parca->duzenle( array( "aciklama" => "MAN12V 281939" ) );
                echo $Barkodlu_Parca->get_return_text();
                echo '<br>';

                $Barkodlu_Parca->garanti_guncelle( "2017-06-08");
                $Barkodlu_Parca->hurda_yap();
                $Barkodlu_Parca->revize_yap();
                $Barkodlu_Parca->kullanildi_yap();

                echo $Barkodlu_Parca->get_return_text();
                echo '<br>';
            } else {
                echo "Böyle bir parça yok.";
                echo '<br>';
            }



            /************  BARKODSUZ PARÇA EKLEME ***********/
            $Barkodsuz_Parca = new Barkodsuz_Parca();
            $Barkodsuz_Parca->ekle(array(
                "isim"      => "Sol",
                "tip"       => $Barkodsuz_Parca_Tipi->get_details("gid"),
                "miktar"    => 5
            ));
            echo $Barkodsuz_Parca->get_return_text();
            echo '<br>';

            /************  BARKODSUZ PARÇA DÜZENLEME ***********/
            $Barkodsuz_Parca = new Barkodsuz_Parca( "GTSPATIPBALATABSSAG" );
            if( $Barkodsuz_Parca->exists() ){
                $Barkodsuz_Parca->kullan(5);
                $Barkodsuz_Parca->stok_ekle(20);
                echo $Barkodsuz_Parca->get_return_text();
                echo '<br>';
            } else {
                echo "Böyle bir barkodsuz parça yok.";
                echo '<br>';
            }


        }

        public function parca_giris_test(){

            $Barkodlu_Parca_Tipi  = new Parca_Tipi("Kaliper");
            $TOTAL_PARCALAR = array();

            /****** JS ILE POST EDILECEK ARRAY ******/
            $barkodlu_input_form_toplu = array(
                array(
                    "aciklama"          => "Barkodlu açıklama",
                    "tip"               => $Barkodlu_Parca_Tipi->get_details("gid"),
                    "fatura_no"         => 350,
                    "adet"              => 5,
                    "satici_firma"      => "TEST FİRMA",
                    "garanti_suresi"    => Common::get_current_date()
                ),
                array(
                    "aciklama"          => "Barkodlu açıklama 22",
                    "tip"               => $Barkodlu_Parca_Tipi->get_details("gid"),
                    "adet"              => 3,
                    "fatura_no"         => 350,
                    "satici_firma"      => "TEST FİRMA",
                    "garanti_suresi"    => Common::get_current_date()
                )
            );

            /****** JS ILE POST EDILECEK ARRAY ******/
            $barkodsuz_input_form_toplu = array(
                array(
                    "stok_kodu"             => "GTSPATIPBALATABSSAG",
                    "eklenecek_miktar"      => 15,
                    "fatura_no"             => 350,
                    "satici_firma"          => "TEST FİRMA"
                ),
                array(
                    "stok_kodu"             => "GTSPATIPBALATABSSOL",
                    "eklenecek_miktar"      => 20,
                    "fatura_no"             => 350,
                    "satici_firma"          => "TEST FİRMA"
                )
            );

            // js den gelen verilerle objeler olusturuyoruz
            // bu objeleri bi array de topluyoruz
            foreach( $barkodlu_input_form_toplu as $barkodlu_parca ){

                // gelen miktar kadar stok kodu farkli olacak sekilde parcalari olusturuyoruz
                for( $x = 0; $x < $barkodlu_parca["adet"]; $x++ ){
                    $Barkodlu_Parca = new Barkodlu_Parca();
                    $Barkodlu_Parca->set_gecici_data(array(
                        "ptip"               => Parca_Tipi::$BARKODLU,
                        "aciklama"          => $barkodlu_parca["aciklama"],
                        "tip"               => $barkodlu_parca["tip"],
                        "fatura_no"         => $barkodlu_parca["fatura_no"],
                        "satici_firma"      => $barkodlu_parca["satici_firma"],
                        "garanti_suresi"    => $barkodlu_parca["garanti_suresi"]
                    ));
                    // direk obje olarak ekliyoruz
                    $TOTAL_PARCALAR[] = $Barkodlu_Parca;
                }
            }

            foreach( $barkodsuz_input_form_toplu as $barkodsuz_parca ){
                // barkodsuz parcalarda direk stok kodunu alicaz ve miktar arttircaz

                $Barkodsuz_Parca = new Barkodsuz_Parca( $barkodsuz_parca["stok_kodu"] );
                $Barkodsuz_Parca->add_gecici_data(array(
                    "ptip"                 => Parca_Tipi::$BARKODSUZ,
                    "eklenecek_miktar"     => $barkodsuz_parca["eklenecek_miktar"],
                    "fatura_no"            => $barkodsuz_parca["fatura_no"],
                    "satici_firma"         => $barkodsuz_parca["satici_firma"]
                ));
                $TOTAL_PARCALAR[] = $Barkodsuz_Parca;
            }

            $Parca_Girisi = new Parca_Girisi();
            $Parca_Girisi->ekle( $TOTAL_PARCALAR );
            echo $Parca_Girisi->get_return_text();

            //print_r( $TOTAL_PARCALAR );

        }

        public function personel_test(){

            $Yeni_Personel = new Personel();
            $Yeni_Personel->ekle(array(
                "seviye"        => Personel::$SURUCU,
                "sicil_no"      => "",
                "isim"          => "Oğuzhan Avinç",
                "eposta"        => "oguz@gmail.com",
                "pass"          => "123",
                "telefon_1"     => "055548745",
                "telefon_2"     => ""
            ));
            echo $Yeni_Personel->get_return_text();

            /*$Personel = new Personel("Ahmet Kanbur");
            $Personel->eposta_duzenle( "veli@gmail.com");
            echo $Personel->get_return_text();*/

        }

        public function auto_login_test(){
            $Auto_Login = new Auto_Login;
            if( $Auto_Login->check() ) {
                $Login = new Login;
                $Login->auto_action($Auto_Login->get_user_id());
                echo 'Oto giriş okey.';
            } else{
                echo 'Oto giris yapilamadi.';
            }
            echo "<br>" . $Auto_Login->get_return_text();
        }

        public function login_test(){
            $Login = new Login();

            if( !$Login->action(array( "eposta" => "amo@amo.com", "pass" => "123", "remember_me" => true ) ) ){
                echo 'Login patladı. <br> ';
            }
            echo $Login->get_return_text() ." <br>";
        }

        public function logout_test(){
            $Logout = new Logout();
            $Logout->action();
        }

        public function aktive_user_test(){
            echo Active_User::get_details("eposta") . "  " . Active_User::get_details("id");
        }

        public function is_emri_formu_test(){

            $form_detaylar_input = array(
                "plaka"             => "34 YG 3831",
                "aktif_kapi_no"     => "",
                "gelis_km"          => 275450,
                "surucu"            => "GTSPERSTEST",
                "gelis_tarih"       => "2017-03-07 12:20",
                "cikis_tarih"       => "2017-03-07 19:12",
                "sikayet"           => "Sikayet",
                "ariza_tespit"      => "Ariza tespit",
                "yapilan_onarim"    => "Yapilan onarim",
                "durum"             => 1
            );

            // parca_kontrol = 1 ( STOĞA EKLENMİŞ ) --- parca_kontrol = 2 ( STOĞA EKLENMEMİŞ BİZ EKLİCEZ )
            $Barkodlu_Parca_Tipi  = new Parca_Tipi("Kaliper");

            $form_aractan_cikanlar = array(
                array(
                    "parca_kontrol" => 1,
                    "tip"                       => Parca_Tipi::$BARKODLU,
                    "stok_kodu"                 => "GTSPATIPKALIPERBLJgUEGF8o5fKMEmdWBPtrF01o4KOnvnkucKwkScRY",
                    "durum"                     => Parca_Tipi::$REVIZE,
                    "revizyon_aciklamasi"       => "Revizyon açıklaması"
                ),
                array(
                    "parca_kontrol" => 1,
                    "tip"           => Parca_Tipi::$BARKODSUZ,
                    "stok_kodu"     => "GTSPATIPBALATABSONSAG",
                    "durum"         => Parca_Tipi::$HURDA,
                    "miktar"        => 2
                ),
                array(
                    "parca_kontrol"         => 2,
                    "aciklama"              => "12V",
                    "parca_tipi"            => $Barkodlu_Parca_Tipi->get_details("gid"),
                    "durum"                 => Parca_Tipi::$REVIZE,
                    "revizyon_aciklamasi"   => "Revizyon açıklaması"
                )
            );

            $form_araca_girenler = array(
                array(
                    "tip"       => Parca_Tipi::$BARKODLU,
                    "stok_kodu" => "GTSPATIPKALIPERBLrPoBpnGR89ammviagi2TBRTDz2NXUaeZO6ai1b4Z"
                ),
                array(
                    "tip"       => Parca_Tipi::$BARKODSUZ,
                    "stok_kodu" => "GTSPATIPBALATABSONSAG",
                    "miktar"    => 2
                )
            );

            // TODO form oluşturma esnasinda parçalara rezerv edicez baska bir formda kullanilmasin diye
            // TODO taslaktan geri alma falan da yapabiliriz sonra - tum parcalari eski halinegetirip stoğu arttıran vs.

            $form_personeL_detay = array(
                array(
                    "personel"      => "PERS3",
                    "is_tanimi"     => "İş tanımı obarey",
                    "baslama"       => Common::get_current_datetime(),
                    "bitis"         => Common::get_current_datetime()
                ),
                array(
                    "personel"      => "PERS2",
                    "is_tanimi"     => "İş tanımı obarey",
                    "baslama"       => Common::get_current_datetime(),
                    "bitis"         => Common::get_current_datetime()
                ),
                array(
                    "personel"      => "PERS4",
                    "is_tanimi"     => "İş tanımı obarey",
                    "baslama"       => Common::get_current_datetime(),
                    "bitis"         => Common::get_current_datetime()
                )
            );
            $Form = new Is_Emri_Formu();
            $Form->ekle( $form_detaylar_input, $form_personeL_detay, $form_araca_girenler, $form_aractan_cikanlar );
            echo $Form->get_return_text() ."<br>";
            print_r( $Form->get_eski_data_test());
            print_r( $Form->get_stokta_olmayan_data_test());

        }

        public function revizyon_talebi_test(){
            $Revizyon_Talebi = new Revizyon_Talebi();
            $ekle = $Revizyon_Talebi->ekle(array(
                "form_gid"              => "TESTFORMID",
                "stok_kodu"             => "PARÇASTOKKODU",
                "aciklama"              => "Bunu revize edin",
            ));
            echo $Revizyon_Talebi->get_return_text();

            $Revizyon_Talebi->teklif_ekle(array(
                "talep_gid"             => $Revizyon_Talebi->get_details("gid"),
                "firma"                 => "REV FIRMA",
                "aciklama"              => "10 parçada %3 iskonto",
            ));
        }

        public function parca_talebi_test(){

            $Parca_Talebi = new Parca_Talebi();
            $Parca_Talebi->ekle(array(
                "form_gid"              => "FORMGID", // formsuz girişte 0 olacak
                "parca_tipi"            => "PARCATIBI",
                "adet"                  => 1,
                "aciklama"              => "Alin bundan 10 tane"
            ));
            echo $Parca_Talebi->get_return_text();

            $Parca_Talebi->teklif_ekle(array(
                "talep_gid"             => $Parca_Talebi->get_details("gid"),
                "firma"                 => "REV FIRMA",
                "aciklama"              => "10 parçada %3 iskonto",
            ));

        }

        public function otobus_test(){

            /*$Otobus = new Otobus();
            $Otobus->ekle(array(
                "plaka"             => "34 YG 2992",
                "ruhsat_kapi_kodu"  => "A-1636",
                "marka"             => "Temsa",
                "model"             => "Euro2828",
                "model_yili"        => "2011",
                "sahip"             => "Veli Konstantin",
                "ogs"               => "958785422",
                "durum"             => 1
            ));*/

            $Otobus = new Otobus("34 YG 2992");
            if( $Otobus->exists() ){
                /*$Otobus->duzenle(array(
                    "ruhsat_kapi_kodu"  => "A-1636",
                    "aktif_kapi_kodu"   => "A-1636",
                    "marka"             => "Temsa",
                    "model"             => "Euro2828",
                    "model_yili"        => 2012,
                    "sahip"             => "Veli Konstantin",
                    "ogs"               => "958785422",
                    "durum"             => 1
                ));*/
                //$Otobus->durum_guncelle( Otobus::$AKTIF );
                echo 'OK<br>';
            }
            echo $Otobus->get_return_text();

        }

        public function marka_model_test(){

            $Marka = new Otobus_Marka();
            $Marka->ekle(array( "isim" => "Temsa"));
            echo $Marka->get_return_text();

            $Model = new Otobus_Model();
            $Model->ekle(array(
                "isim" => "Euro 14",
                "marka" => "Temsa"
            ));
            echo $Model->get_return_text();

        }

        public function satici_firma_test(){

            $Firma = new Satici_Firma();
            $Firma->ekle(array(
                "isim"              => "Obarey Elektronik",
                "vergi_dairesi"     => "Beykoz",
                "vergi_no"          => 35708106808,
                "telefon_1"         => "0543 239 0269",
                "telefon_2"         => "",
                "eposta"            => "amo@amo.com",
                "aciklama"          => ""
            ));
            echo $Firma->get_return_text();
            echo $Firma->get_details("isim");

        }

    }





     $db_setup = new DBSetup();
    //$db_setup->tablolari_olustur();
    //$db_setup->parca_tipi_init();


    $GTest = new Gitas_Test();
    //$GTest->gitas_hash_test();
    //$GTest->parca_tipi_test();
    //$GTest->parca_test();
    //$GTest->parca_giris_test();
    //$GTest->personel_test();
    $GTest->auto_login_test();
    //$GTest->login_test();
    //$GTest->logout_test();
    //$GTest->aktive_user_test();
    //$GTest->is_emri_formu_test();
    //$GTest->revizyon_talebi_test();
    //$GTest->parca_talebi_test();
    //$GTest->otobus_test();
    //$GTest->marka_model_test();
    //$GTest->satici_firma_test();


$parca_tipi = "GTSPATIPBALATA";
$Parca_Tipi = new Parca_Tipi( $parca_tipi );
$output = array();
if( $Parca_Tipi->get_details("tip") == Parca_Tipi::$BARKODSUZ ){
    foreach( $Parca_Tipi->varyantlari_listele() as $varyant ){
        $Parca = new Barkodlu_Parca( $varyant["stok_kodu"] );
        $query = DB::getInstance()->query("SELECT * FROM " . DBT_ISEMRI_FORMU_GIRENLER . " WHERE stok_kodu = ?", array( $varyant["stok_kodu"] ) )->results();
        foreach( $query as $cikis ){
            if( $Parca->get_details("tip") == $Parca_Tipi->get_details("gid") ){
                if( isset( $output[ $cikis["form_gid"] ] ) ){
                    $output[ $cikis["form_gid"] ]["miktar"]++;
                } else {
                    $Form = new Is_Emri_Formu( $cikis["form_gid"]  );
                    $output[ $cikis["form_gid"] ] = array(
                        "miktar"        => $cikis["miktar"],
                        "plaka"         => $Form->get_details("plaka"),
                        "tarih"         => $Form->get_details("tarih")
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
    $query = DB::getInstance()->query("SELECT * FROM " . DBT_BARKODLU_PARCALAR . " WHERE tip = ? && ( kullanildi = ? || revize = ? )", array( $Parca_Tipi->get_details("gid") , 1, 1 ) )->results();
    foreach( $query as $parca ){
        $query_cikis = DB::getInstance()->query("SELECT * FROM " . DBT_ISEMRI_FORMU_GIRENLER . " WHERE stok_kodu = ?", array( $parca["stok_kodu"] ) )->results();
        if( count($query_cikis) > 0 ){
            if( isset( $output[ $query_cikis[0]["form_gid"] ] ) ){
                $output[ $query_cikis[0]["form_gid"] ]["miktar"]++;
            } else {
                $Form = new Is_Emri_Formu( $query_cikis[0]["form_gid"]  );
                $output[ $query_cikis[0]["form_gid"] ] = array(
                    "miktar"        => 1,
                    "plaka"         => $Form->get_details("plaka"),
                    "tarih"         => $Form->get_details("tarih")
                );
            }
        }
    }
}
echo '<pre>';
print_r( $output );


