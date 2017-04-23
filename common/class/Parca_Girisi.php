<?php

/**
 * Created by PhpStorm.
 * User: Jeppe
 * Date: 06.03.2017
 * Time: 16:44
 */
class Parca_Girisi extends Data_Out {

    private $eklenenler = array();

    public function __construct( $id = null ){
        $db_keys = array( "id", "gid" );
        parent::__construct( DBT_PARCA_GIRISLERI, $db_keys, $id );
    }

    public function temp_id_olustur(){
        // parca eklerken sayfaya ilk giriste olusturdumuz id
        $this->details["gid"] = Gitas_Hash::hash_olustur( Gitas_Hash::$PARCA_GIRISI );
    }

    public function set_gid( $gid ){
        // ilk parcayi ekledikten sonra parça giriş ID sini aldigimiz method
        // tek bir sayfada eklenen tum parcalar, tek parça giriş kaydı olacak
        $this->details["gid"] = $gid;
    }

    public function ekle( $parcalar ){

        // ilk ekleme
        if( count($this->pdo->query("SELECT * FROM " . $this->table ." WHERE gid = ?", array( $this->details["gid"] ) )->results()) == 0 ){
            $this->pdo->insert( $this->table, array(
                "gid"           => $this->details["gid"],
                "giris_yapan"   => Active_User::get_details("id"),
                "tarih"         => Common::get_current_datetime()
            ));
        }


        foreach( $parcalar as $parca ){
            // barkodlu - barkodsuz ayrimi icin stok_kodu bilgisi var mi diye bakiyoruz
            if( $parca->get_details("ptip") == Parca_Tipi::$BARKODSUZ ){
                // 1 - barkodsuz_parcalar tablosundan miktarı arttırıcaz
                // 2 - barkodsuz_parca_girisleri_icerik e kayit eklicez

                $parca->stok_ekle( $parca->get_details("eklenecek_miktar") );
                $this->pdo->insert( DBT_BARKODSUZ_PARCA_GIRISLERI, array(
                    "parca_giris_gid"   => $this->details["gid"],
                    "stok_kodu"         => $parca->get_details("stok_kodu"),
                    "miktar"            => $parca->get_details("eklenecek_miktar"),
                    "satici_firma"      => $parca->get_details("satici_firma"),
                    "fatura_no"         => $parca->get_details("fatura_no")
                ));
                $Parca_Tipi = new Parca_Tipi ( $parca->get_details("tip") );
                $Firma = new Satici_Firma( $parca->get_details("satici_firma") );
                // js output
                $this->eklenenler[] = array(
                    "tip"       => $Parca_Tipi->get_details("isim"),
                    "aciklama"  => $parca->get_details("aciklama"),
                    "adet"      => $parca->get_details("eklenecek_miktar") . " " . $Parca_Tipi->get_details("miktar_olcu_birimi"),
                    "firma"     => $Firma->get_details("isim") . " - ( Fatura No: ".$parca->get_details("fatura_no")." )"
                );
            } else {
                // 1 - direk parcalar tablosuna parcayi ekliyoruz, parca giris id ile bagliyoruz giriş kaydini
                $parca->ekle(array(
                    "aciklama"          => $parca->get_details("aciklama"),
                    "tip"               => $parca->get_details("tip"),
                    "fatura_no"         => $parca->get_details("fatura_no"),
                    "satici_firma"      => $parca->get_details("satici_firma"),
                    "garanti_suresi"    => $parca->get_details("garanti_suresi"),
                    "parca_giris_id"    => $this->details["gid"],
                    "durum"             => 1
                ));
                $Parca_Tipi = new Parca_Tipi ( $parca->get_details("tip") );
                $Firma = new Satici_Firma( $parca->get_details("satici_firma") );
                $this->eklenenler[] = array(
                    "tip"           => $Parca_Tipi->get_details("isim"),
                    "aciklama"      => $parca->get_details("aciklama"),
                    "adet"          => 1 . " " . $Parca_Tipi->get_details("miktar_olcu_birimi"),
                    "stok_kodu"     => $parca->get_details("stok_kodu"),
                    "firma"         => $Firma->get_details("isim") . " - ( Fatura No: ".$parca->get_details("fatura_no")." )"
                );
                if( !$parca->is_ok() ){
                    $this->return_text = "Parçalar eklenirken bir hata oluştu. Tekrar deneyin.";
                    $this->ok = false;
                    return;
                }
            }
        }
    }

    // parça giriş esnasinda veri aldigimiz metod
    public function get_eklenenler(){
        return $this->eklenenler;
    }

    private function icerik_listele( $tip ){
        if( $tip == Parca_Tipi::$BARKODSUZ ){
            $query = $this->pdo->query("SELECT * FROM " . DBT_BARKODSUZ_PARCA_GIRISLERI . " WHERE parca_giris_gid = ?", array( $this->details["gid"] ) )->results();
        } else {
            $query = $this->pdo->query("SELECT * FROM " . DBT_BARKODLU_PARCALAR . " WHERE parca_giris_id = ?", array( $this->details["gid"]) )->results();
            $miktar = 1;
        }
        foreach( $query as $parca ){
            $Firma = new Satici_Firma( $parca["satici_firma"] );
            $Parca = Parca::get( $parca["stok_kodu"] );
            $Parca_Tipi = new Parca_Tipi( $Parca->get_details("tip"));
            if( isset($parca["miktar"] ) ) $miktar = $parca["miktar"];
            $data = array(
                "stok_kodu"     => $parca["stok_kodu"],
                "parca_tipi"    => $Parca_Tipi->get_details("isim"),
                "aciklama"      => $Parca->get_details("aciklama"),
                "fatura_no"     => $parca["fatura_no"],
                "satici_firma"  => $Firma->get_details("isim"),
                "miktar"        => $miktar
            );
            if( $tip == Parca_Tipi::$BARKODLU ) $data["garanti_suresi"] = $parca["garanti_suresi"];
            $data["miktar"] .= " " . $Parca_Tipi->get_details("miktar_olcu_birimi");
            $this->details["giris_icerik"][] = $data;
        }
    }

    public function giris_icerik_listele(){
        $this->icerik_listele(Parca_Tipi::$BARKODSUZ);
        $this->icerik_listele(Parca_Tipi::$BARKODLU);
    }

    public function detay_html(){
        $Giris_Yapan = new Personel( $this->details["giris_yapan"] );
        $statdata = array(
            array(
                "header" => "GİRİŞ DETAYLARI",
                "items"  => array(
                    array( "key" => "GİRİŞ YAPAN", "val" => $Giris_Yapan->get_details("isim")  ),
                    array( "key" => "TARİH", "val" => $this->details["tarih"] ),
                )
            )
        );
        $icerik_html = "";
        foreach( $this->details["giris_icerik"] as $parca ){
                $icerik_html .=  '<tr>'
                                .    '<td>'.$parca["parca_tipi"].'</td>'
                                .    '<td>'.$parca["aciklama"].'</td>'
                                .    '<td>'.$parca["miktar"].'</td>'
                                .    '<td title="'.$parca["stok_kodu"].'">'.substr($parca["stok_kodu"], 0, 25 ).'</td>'
                                .    '<td>'.$parca["fatura_no"].'</td>'
                                .    '<td>'.$parca["satici_firma"].'</td>'
                                . '</tr>';

        }
        return Popup_Stats::init( $statdata )
        .'<table class="obarey-table">'
        .   '<thead>'
        .       '<tr>'
        .           '<td>Parça Tipi</td>'
        .           '<td>Açıklama</td>'
        .           '<td>Miktar</td>'
        .           '<td>Stok Kodu</td>'
        .           '<td>Fatura No</td>'
        .           '<td>Satıcı Firma</td>'
        .       '</tr>'
        .   '</thead>'
        .   '<tbody>'
        .       $icerik_html
        .   '</tbody>'
        .'</table> <a href="'.URL_YAZDIRMA_TEMA_PARCA_GIRISI.'?pargid='.$this->details["gid"].'" target="_blank" class="mnbtn mor yazdirbtn">BARKODLARI YAZDIR</a>';
    }

}