<?php

/**
 * Created by PhpStorm.
 * User: Jeppe
 * Date: 07.03.2017
 * Time: 21:32
 */
class Revizyon_Talebi extends Data_Out {

    public static   $TAMAMLANDI = 1,
                    $AKTIF = 2,
                    $TEKLIF_ONAYI_BEKLENIYOR = 3,
                    $PARCA_BEKLENIYOR = 4;

    public function __construct( $id = null ){
        $db_keys = array( "id", "gid" );
        parent::__construct( DBT_REVIZYON_TALEPLERI, $db_keys, $id );
    }

    public function ekle( $input ){

        $this->details["gid"] = Gitas_Hash::hash_olustur(Gitas_Hash::$REVIZYON_TALEP, array( "form_id" => $input["form_gid"] ) );
        $insert = $this->pdo->insert( $this->table, array(
            "gid"                   => $this->details["gid"],
            "form_gid"              => $input["form_gid"],
            "stok_kodu"             => $input["stok_kodu"],
            "aciklama"              => $input["aciklama"],
            "duzenleyen_personel"   => Active_User::get_details("id"),
            "durum"                 => self::$AKTIF,
            "tarih"                 => Common::get_current_datetime()
        ));

        if( !$insert ){
            $this->return_text = "Talep eklenirken bir hata oluştu.";
            return false;
        }
        $this->return_text = "Talep eklendi.";
        return true;
    }

    public function teklif_ekle( $input ){
        $Teklif = new Revizyon_Talep_Teklifi();
        if( !$Teklif->ekle($input) ){
            $this->return_text = $Teklif->get_return_text();
            return false;
        }
        $this->return_text = $Teklif->get_return_text();
        return true;
    }

    public function durum_guncelle( $durum ){
        if( $durum == Revizyon_Talebi::$TAMAMLANDI ){
            $this->pdo->query("UPDATE " . $this->table . " SET durum = ?, ilgili_personel = ?, tamamlanma_tarihi = ? WHERE gid = ?",array( $durum, Active_User::get_details("id"), Common::get_current_datetime(), $this->details["gid"]));
        } else {
            $this->pdo->query("UPDATE " . $this->table . " SET durum = ? WHERE gid = ?",array( $durum, $this->details["gid"]));
        }

    }

    public function teklifleri_listele(){
        return $this->pdo->query("SELECT * FROM " . DBT_REVIZYON_TALEP_TEKLIFLERI . " WHERE talep_gid = ?", array( $this->details["gid"]))->results();
    }

    public function teklifler_html(){
        $html = "";

        $teklifler = $this->teklifleri_listele();
        $c = count($teklifler);
        rsort( $teklifler );
        $evoddclass = "even";

        $onayla_btn = "";
        if( $this->get_details("durum") != self::$TAMAMLANDI && $this->get_details("durum") != self::$PARCA_BEKLENIYOR ){
            $onayla_btn = '<button type="button" class="mnbtn mor onayla">ONAYLA</button>';
        }
        foreach( $teklifler as $teklif ){
            $Personel = new Personel( $teklif["duzenleyen_personel"] );
            $Firma = new Satici_Firma( $teklif["firma"]);
            $html .=    '<div class="teklif '.$evoddclass.' " data-id="'.$this->details["id"].'">'
                        .   '<span class="teklif-info">Teklif '.$c.'</span>'
                        .   '<ul>'
                        .       '<li>Teklifi Veren: '.$Personel->get_details("isim").'</li>'
                        .       '<li onmouseover="Obarey_Tooltip(\'text\', \' Vergi No: '.$Firma->get_details("vergi_no").' <br> Vergi Dairesi: '.$Firma->get_details("vergi_dairesi").' <br> Telefon: '.$Firma->get_details("telefon_1").' <br> Eposta: '.$Firma->get_details("eposta").' \', this, event)" >Firma: '.$Firma->get_details("isim").'</li>'
                        .       '<li>Tarih: '.$teklif["tarih"].'</li>'
                        .   '</ul>'
                        .   '<div class="aciklama">'
                        .       '<label>Açıklama</label>'
                        .       '<span>'.$teklif["aciklama"].'</span>'
                        .   '</div>'
                        .   $onayla_btn
                        .'</div>';
            if( $evoddclass == "even" ) {
                $evoddclass = "odd";
            } else {
                $evoddclass = "even";
            }
            $c--;
        }
        return $html;
    }

    public function durum_str(){
        if( $this->details["durum"] == self::$PARCA_BEKLENIYOR ) return "Onaylandı. Parçalar bekleniyor.";
        if( $this->details["durum"] == self::$TAMAMLANDI ) return "Tamamlandı.";
        if( $this->details["durum"] == self::$TEKLIF_ONAYI_BEKLENIYOR ) return "Teklif onayı bekleniyor.";
        if( $this->details["durum"] == self::$AKTIF ) return "Aktif.";
    }

}