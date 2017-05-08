<?php

/**
 * Created by PhpStorm.
 * User: Jeppe
 * Date: 05.05.2017
 * Time: 12:48
 */
class Parca_Girisi extends Data_Out{

    private $eklenenler = array();

    public function __construct( $id = null ){
        $db_keys = array( "id", "gid" );
        parent::__construct( DBT_PARCA_GIRISLERI, $db_keys, $id );
    }

    public function ekle( $input ){

        // kaydi tekrarlamama kontrolu
        if( count($this->pdo->query("SELECT * FROM " . $this->table ." WHERE gid = ?", array( $this->details["gid"] ) )->results()) == 0 ){
            $this->pdo->insert( $this->table, array(
                "gid"           => $this->details["gid"],
                "giris_yapan"   => Active_User::get_details("id"),
                "tarih"         => Common::get_current_datetime()
            ));
        }

        $Parca_Tipi = new Parca_Tipi( $input["parca_tipi"] );
        $Firma = new Satici_Firma( $input["satici_firma"] );
        if( $Parca_Tipi->get_details("tip") == Parca_Tipi::$BARKODLU ){
            for( $j = 0; $j < $input["adet"]; $j++ ){
                $Parca = new Parca();
                $Parca->barkodlu_ekle($input);

                // js output
                $this->eklenenler[] = array(
                    "tip"       => $Parca_Tipi->get_details("isim"),
                    "aciklama"  => $input["aciklama"],
                    "adet"      => $input["adet"] . " " . $Parca_Tipi->get_details("miktar_olcu_birimi"),
                    "stok_kodu" => $Parca->get_details("stok_kodu"),
                    "firma"     => $Firma->get_details("isim") . " - ( Fatura No: ".$input["fatura_no"]." )"
                );
            }
        } else {
            if( isset($input["varyant_gid"] ) ){
                $Parca = new Parca( $input["parca_tipi"], $input["varyant_gid"] );
            } else {
                $Parca = new Parca( $input["parca_tipi"], "YOK" );
            }

            $Parca->barkodsuz_ekle($input);
            $this->eklenenler[] = array(
                "tip"       => $Parca_Tipi->get_details("isim"),
                "aciklama"  => $input["aciklama"],
                "adet"      => $input["adet"] . " " . $Parca_Tipi->get_details("miktar_olcu_birimi"),
                "firma"     => $Firma->get_details("isim") . " - ( Fatura No: ".$input["fatura_no"]." )"
            );

            $this->pdo->insert( DBT_BARKODSUZ_PARCA_GIRISLERI, array(
                "parca_giris_gid"   => $this->details["gid"],
                "stok_kodu"         => $Parca->get_details("stok_kodu"),
                "satici_firma"      => $input["satici_firma"],
                "fatura_no"         => $input["fatura_no"],
                "miktar"            => $input["adet"]
            ));
        }
    }

    private function icerik_listele( $tip ){
        if( $tip == Parca_Tipi::$BARKODSUZ ){
            $query = $this->pdo->query("SELECT * FROM " . DBT_BARKODSUZ_PARCA_GIRISLERI . " WHERE parca_giris_gid = ?", array( $this->details["gid"] ) )->results();
        } else {
            $query = $this->pdo->query("SELECT * FROM " . DBT_PARCALAR . " WHERE parca_giris_gid = ?", array( $this->details["gid"]) )->results();
            $miktar = 1;
        }
        foreach( $query as $parca ){
            $Firma = new Satici_Firma( $parca["satici_firma"] );
            $Parca = new Parca( $parca["stok_kodu"] );
            $Parca_Tipi = new Parca_Tipi( $Parca->get_details("parca_tipi"));
            if( isset($parca["miktar"] ) ) $miktar = $parca["miktar"];
            $Varyant = new Varyant( $Parca->get_details("varyant_gid") );
            $data = array(
                "stok_kodu"     => $parca["stok_kodu"],
                "parca_tipi"    => $Parca_Tipi->get_details("isim"),
                "aciklama"      => $Parca->get_details("aciklama"),
                "fatura_no"     => $parca["fatura_no"],
                "satici_firma"  => $Firma->get_details("isim"),
                "miktar"        => $miktar,
                "varyant_gid"   => $Varyant->get_details("isim")
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
                .    '<td>'.$parca["varyant_gid"].'</td>'
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
            .           '<td>Varyant</td>'
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

    // parça giriş esnasinda veri aldigimiz metod
    public function get_eklenenler(){
        return $this->eklenenler;
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


}