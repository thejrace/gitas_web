<?php

/**
 * Created by PhpStorm.
 * User: Jeppe
 * Date: 06.03.2017
 * Time: 11:32
 */
class Gitas_Hash{

    public static   $PF_GITAS = "GTS",
                    $PF_PARCATIPI = "PATIP",
                    $PF_BARKODLU_PARCA = "BL",
                    $PF_BARKODSUZ_PARCA = "BS",
                    $PF_PARCA_GIRISI = "PGIR",
                    $PF_PERSONEL = "GPERS",
                    $PF_ISEMRI_FORMU = "IEF",
                    $PF_REVIZYON_TALEP = "REVTA",
                    $PF_PARCA_TALEP = "PARTA",
                    $PF_SATICI_FRIMA = "SFIRM";

    public static   $BARKODSUZ_PARCA    = 0,
                    $BARKODLU_PARCA     = 1,
                    $PARCA_TIPI         = 2,
                    $PARCA_GIRISI       = 3,
                    $IS_EMRI_FORMU      = 5,
                    $PERSONEL           = 6,
                    $MESAJ              = 7,
                    $ARIZA_BILDIRIMI    = 8,
                    $PARCA_TALEP        = 9,
                    $TAKVIM_KAYIT       = 10,
                    $REVIZYON_TALEP     = 11,
                    $REVIZYON_TEKLIF    = 12,
                    $STOK_FIRMA         = 13;


    // @data -> isimlendirme icin gelecek verileri tutan array
    public static function hash_olustur( $tip, $data = array() ){
        $hash = "";
        switch( $tip ){

            case self::$BARKODLU_PARCA:
                // unique bulana kadar kontrol ediyoruz db yi
                do {
                    $hash = $data["parca_tipi"] . self::$PF_BARKODLU_PARCA . Common::generate_random_string( 40 );
                    $check_query = DB::getInstance()->query("SELECT * FROM " . DBT_BARKODLU_PARCALAR . " WHERE stok_kodu = ?", array( $hash ) )->results();
                } while ( count( $check_query )  > 0 );

            break;

            case self::$BARKODSUZ_PARCA:
                $hash = $data["parca_tipi"] . self::$PF_BARKODSUZ_PARCA . self::turkce_sef($data["aciklama"]);
            break;

            case self::$PARCA_TIPI:
                $hash = self::$PF_GITAS . self::$PF_PARCATIPI . self::turkce_sef($data['isim'] );
            break;

            case self::$PARCA_GIRISI:
                // unique bulana kadar kontrol ediyoruz db yi
                do {
                    $hash = self::$PF_GITAS . self::$PF_PARCA_GIRISI . Common::generate_random_string( 40 );
                    $check_query = DB::getInstance()->query("SELECT * FROM " . DBT_PARCA_GIRISLERI . " WHERE gid = ?", array( $hash ) )->results();
                } while ( count( $check_query )  > 0 );

            break;

            case self::$IS_EMRI_FORMU:
                // unique bulana kadar kontrol ediyoruz db yi
                do {
                    $hash = self::$PF_GITAS . self::$PF_ISEMRI_FORMU . self::turkce_sef( $data["plaka"] ) . Common::generate_random_string( 20 );
                    $check_query = DB::getInstance()->query("SELECT * FROM " . DBT_ISEMRI_FORMLARI . " WHERE gid = ?", array( $hash ) )->results();
                } while ( count( $check_query )  > 0 );
            break;

            case self::$PERSONEL:
                $isim_tekli = "";
                $gid_isim = explode(" ", $data["isim"] );
                foreach( $gid_isim as $isim_part ) $isim_tekli .= substr( self::turkce_sef($isim_part), 0, 1 );
                $hash = self::$PF_GITAS . self::$PF_PERSONEL . $isim_tekli . $data["seviye"];

            break;


            case self::$MESAJ:

            break;

            case self::$ARIZA_BILDIRIMI:

            break;

            case self::$PARCA_TALEP:
                do {
                    $hash =  self::$PF_GITAS . self::$PF_PARCA_TALEP . $data["parca_tipi"] . Common::generate_random_string( 40 );
                    $check_query = DB::getInstance()->query("SELECT * FROM " . DBT_PARCA_TALEPLERI . " WHERE gid = ?", array( $hash ) )->results();
                } while ( count( $check_query )  > 0 );
            break;

            case self::$TAKVIM_KAYIT:

            break;

            case self::$REVIZYON_TALEP:

                do {
                    $hash =  self::$PF_GITAS .  self::$PF_REVIZYON_TALEP . $data["form_id"] . Common::generate_random_string( 40 );
                    $check_query = DB::getInstance()->query("SELECT * FROM " . DBT_REVIZYON_TALEPLERI . " WHERE gid = ?", array( $hash ) )->results();
                } while ( count( $check_query )  > 0 );

            break;

            case self::$REVIZYON_TEKLIF:

            break;

            case self::$STOK_FIRMA:
                do {
                    $hash =  self::$PF_GITAS . self::$PF_SATICI_FRIMA . $data["vergi_no"] . Common::generate_random_string( 40 );
                    $check_query = DB::getInstance()->query("SELECT * FROM " . DBT_STOK_FIRMARLAR . " WHERE gid = ?", array( $hash ) )->results();
                } while ( count( $check_query )  > 0 );

            break;

        }

        return $hash;

    }


    public static function turkce_sef ( $fonktmp ) {
        $turkcefrom = array("/Ğ/","/Ü/","/Ş/","/İ/","/Ö/","/Ç/","/ğ/","/ü/","/ş/","/ı/","/ö/","/ç/");
        $turkceto   = array("G","U","S","I","O","C","g","u","s","i","o","c");
        $fonktmp = preg_replace("/[^0-9a-zA-ZÄzÜŞİÖÇğüşıöç]/"," ",$fonktmp);
        // Türkçe harfleri ingilizceye çevir
        $fonktmp = preg_replace($turkcefrom,$turkceto,$fonktmp);
        // Birden fazla olan boşlukları tek boşluk yap
        $fonktmp = preg_replace("/ +/"," ",$fonktmp);
        // Boşukları - işaretine çevir
        $fonktmp = preg_replace("/ /","",$fonktmp);
        // Whitespace
        $fonktmp = preg_replace("/\s/","",$fonktmp);
        // Başta ve sonda - işareti kaldıysa yoket
        $fonktmp = preg_replace("/^-/","",$fonktmp);
        $fonktmp = preg_replace("/-$/","",$fonktmp);
        $returnstr = $fonktmp;
        return strtoupper($returnstr);
    }

}