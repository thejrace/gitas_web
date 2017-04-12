<?php
/**
 * Created by PhpStorm.
 * User: Jeppe
 * Date: 21.03.2017
 * Time: 17:00
 */
    require '../inc/init.php';


    if( $_POST ){

        $OK = 1;
        $TEXT = "";
        $DATA = array();

        switch( Input::get("req") ){

            case 'veri_al':
                $query = DB::getInstance()->query("SELECT * FROM " . DBT_PARCA_TIPLERI )->results();
                foreach( $query as $parca_tipi ){
                    $right_content_var = false;
                    $color = GitasDT_CSS::$C_BEYAZ;
                    $miktar = 0;
                    if( $parca_tipi["tip"] == Parca_Tipi::$BARKODLU ){
                        $stok_query = DB::getInstance()->query("SELECT * FROM " . DBT_BARKODLU_PARCALAR . " WHERE tip = ? && kullanildi = ? && durum = ?", array($parca_tipi["gid"], 0, 1))->results();
                        $miktar = count($stok_query);
                    } else {
                        $stok_query = DB::getInstance()->query("SELECT * FROM " . DBT_BARKODSUZ_PARCALAR . " WHERE tip = ?", array( $parca_tipi["gid"]))->results();
                        foreach( $stok_query as $item ){
                            $miktar += $item["miktar"];
                        }
                    }
                    $miktar_str = $miktar . " " . $parca_tipi["miktar_olcu_birimi"];
                    if( $miktar <= 0 || ( $parca_tipi["kritik_seviye_limiti"] > 0 && $parca_tipi["kritik_seviye_limiti"] > $miktar ) ){
                        $color = GitasDT_CSS::$C_KIRMIZI;
                        $right_content_var = true;
                        $right_content = array(
                            "ico" => GitasDT_CSS::$ICO_WARNING1,
                            "text" => "Stok Kritik Seviyede"
                        );
                    }
                    $output = array(
                        "data_id"   => $parca_tipi["gid"],
                        "ico"       => GitasDT_CSS::$ICO_PARCA_TIPI, // js de tanimli
                        "bigtitle"  => $parca_tipi["isim"],
                        "subtitle"  => $miktar_str,
                        "color"     => $color,
                        "font"      => GitasDT_CSS::$F_BOLD,
                        "icoset"    => GitasDT_CSS::$ICOSET_PARCA_TIPI, // parcatipi,
                        "part2"     => true
                    );
                    if( $right_content_var ) $output["right_content"] = $right_content;
                    $DATA[] = $output;
                }
            break;




        }

        $output = json_encode(array(
            "ok"    => $OK,
            "text"  => $TEXT,
            "data"  => $DATA,
            "oh"    => $_POST
        ));
        echo $output;
        die;

    }
//                $DATA = array(
//
//                    array(
//                        "data_id" => "GTSPATIPBALATA",
//                        "ico" => GitasDT_CSS::$ICO_PARCA_TIPI, // js de tanimli
//                        "bigtitle" => "BANT",
//                        "subtitle" => "100 Adet",
//                        "color" => GitasDT_CSS::$C_BEYAZ,
//                        "font"  => GitasDT_CSS::$F_BOLD,
//                        "icoset" => GitasDT_CSS::$ICOSET_PARCA_TIPI, // parcatipi
//                        "part2"  => true
//                    ),
//                    array(
//                        "data_id" => "GTSPATIPBALATA",
//                        "ico" => GitasDT_CSS::$ICO_PARCA_TIPI, // js de tanimli
//                        "bigtitle" => "AMOREY",
//                        "subtitle" => "100 Adet",
//                        "color" => GitasDT_CSS::$C_BEYAZ,
//                        "font"  => GitasDT_CSS::$F_BOLD,
//                        "icoset" => GitasDT_CSS::$ICOSET_PARCA_TIPI, // parcatipi
//                        "part2"  => true
//                    ),
//                    array(
//                        "data_id" => "GTSPATIPBALATA",
//                        "ico" => GitasDT_CSS::$ICO_PARCA_TIPI,
//                        "bigtitle" => "KALİPER",
//                        "subtitle" => "100 Adet",
//                        "color" => GitasDT_CSS::$C_BEYAZ,
//                        "font"  => GitasDT_CSS::$F_BOLD,
//                        "icoset" => GitasDT_CSS::$ICOSET_PARCA_TIPI, // parça tipi --> js de taniyip iconlari yerlestircez veri azalsin
//                        "right_content" => array(
//                            "ico" => GitasDT_CSS::$ICO_WARNING1,
//                            "text" => "Stok Kritik Seviyede"
//                        )
//                    )
//
//                );