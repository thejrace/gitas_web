<?php

/**
 * Created by PhpStorm.
 * User: Jeppe
 * Date: 05.05.2017
 * Time: 12:49
 */
class Parca{

    private $pdo, $return_text, $details = array(), $table = DBT_PARCALAR,
            $ok = true, $exists = false;

    public static   $DREVIZE = "R",
                    $DHURDA = "H",
                    $DKAYIP = "Y",
                    $DBILGIYOK = "BY";

    public function __construct( $parca_tipi_veya_stok_kodu = null, $varyant_gid = null ){
        $this->pdo = DB::getInstance();
        $query = array();
        if( $parca_tipi_veya_stok_kodu != null && $varyant_gid != null ){
            if( $varyant_gid == "YOK" ){
                $query = $this->pdo->query("SELECT * FROM " . $this->table . " WHERE parca_tipi = ? && varyant_gid IS NULL", array( $parca_tipi_veya_stok_kodu ) )->results();
            } else {
                $query = $this->pdo->query("SELECT * FROM " . $this->table . " WHERE parca_tipi = ? && varyant_gid = ?", array( $parca_tipi_veya_stok_kodu, $varyant_gid ) )->results();
            }

        } else if( $parca_tipi_veya_stok_kodu != null ){
            $query = $this->pdo->query("SELECT * FROM " . $this->table . " WHERE stok_kodu = ?", array( $parca_tipi_veya_stok_kodu) )->results();
        }
        if( count($query) == 1 ){
            $this->details = $query[0];
            $this->exists = true;
        }
    }

    // tek tek ekleme yapiyoruz
    public function barkodlu_ekle( $input ){
        $this->details["stok_kodu"] = Gitas_Hash::hash_olustur( Gitas_Hash::$BARKODLU_PARCA, array( "parca_tipi" => $input["parca_tipi"] ) );
        $parca_data = array(
            "stok_kodu"         => $this->details["stok_kodu"],
            "parca_tipi"        => $input["parca_tipi"],
            "aciklama"          => $input["aciklama"],
            "fatura_no"         => $input["fatura_no"],
            "satici_firma"      => $input["satici_firma"],
            "parca_giris_gid"   => $input["parca_giris_gid"]
        );
        if( isset( $input["varyant_gid"]) ) $parca_data["varyant_gid"] = $input["varyant_gid"];
        ( isset( $input["durum"]) ) ? $parca_data["durum"] = $input["durum"] : $parca_data["durum"] = "AK";
        if( isset($input["garanti_baslangic"] ) && trim($input["garanti_baslangic"]) != "" ){
            $parca_data["garanti_baslangic"] = $input["garanti_baslangic"];
            $parca_data["garanti_suresi"] = $input["garanti_suresi"];
        }
        if( !$this->pdo->insert( $this->table, $parca_data ) ){
            $this->ok = false;
            $this->return_text = "Parça eklenirken bir hata oluştu. Tekrar deneyin.";
            return;
        }
        $this->return_text = "Parça(lar) eklendi.";
    }

    public function barkodlu_kullan(){
        $this->pdo->query("UPDATE " . $this->table . " SET durum = ? WHERE stok_kodu = ?", array( Parca_Tipi::$ARACTA, $this->details["stok_kodu"]));
    }

    // miktar arttırma yapiyoruz
    public function barkodsuz_ekle( $input ){
        if( !$this->exists() ){
            // parcalar tablosunda hic kayit yok, ekliyoruz
            $this->details["stok_kodu"] = Gitas_Hash::hash_olustur( Gitas_Hash::$BARKODSUZ_PARCA, array("parca_tipi" => $input["parca_tipi"] ));
            $this->details["miktar"] = 0;
            $parca_data = array(
                "stok_kodu"     => $this->details["stok_kodu"],
                "parca_tipi"    => $input["parca_tipi"],
                "aciklama"      => $input["aciklama"],
                "miktar"        => $this->details["miktar"],
                "durum"         => "AK"
            );
            if( isset( $input["varyant_gid"]) ) $parca_data["varyant_gid"] = $input["varyant_gid"];
            $this->pdo->insert($this->table, $parca_data);
        }
        $this->barkodsuz_miktar_guncelle( $this->details["miktar"] + $input["adet"] );
    }

    public function barkodsuz_kullan( $kullanilan_miktar ){
        $this->barkodsuz_miktar_guncelle( $this->details["miktar"] - $kullanilan_miktar );
    }

    public function barkodsuz_miktar_guncelle( $yeni_miktar ){
        $this->pdo->query("UPDATE " . $this->table . " SET miktar = ? WHERE stok_kodu = ?", array( $yeni_miktar, $this->details["stok_kodu"] ) );
    }

    public function durum_guncelle( $form_durum ){
        if( $form_durum == Parca::$DREVIZE ){
            $durum = Parca_Tipi::$REVIZYONDA;
        } else if( $form_durum == Parca::$DHURDA ){
            $durum = Parca_Tipi::$HURDA;
        } else {
            $durum = Parca_Tipi::$KAYIP;
        }
        $this->pdo->query("UPDATE " . $this->table . " SET durum = ? WHERE stok_kodu = ?", array( $durum, $this->details["stok_kodu"]));
    }

    public function get_details( $key = null ){
        if( isset($key) ){
            if( $this->details[$key] == null ) return "[YOK]";
            return $this->details[$key];
        }
        return $this->details;
    }

    public function get_return_text(){
        return $this->return_text;
    }

    public function exists(){
        return $this->exists;
    }

    public function is_ok(){
        return $this->ok;
    }

}