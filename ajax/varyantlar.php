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

                if( in_array( Aktiviteler::VARYANTLAR_DT, $KULLANICI_IZINLER ) ) {
                    $query = DB::getInstance()->query("SELECT * FROM " . DBT_VARYANTLAR . " WHERE parent IS NULL")->results();
                    foreach ($query as $varyant ) {
                        $color = GitasDT_CSS::$C_BEYAZ;
                        $output = array(
                            "data_id" => $varyant["gid"],
                            "ico" => GitasDT_CSS::$ICO_VARYANT, // js de tanimli
                            "bigtitle" => $varyant["isim"],
                            "subtitle" => "",
                            "color" => $color,
                            "font" => GitasDT_CSS::$F_BOLD,
                            "icoset" => GitasDT_CSS::$ICOSET_VARYANT,
                            "part2" => true
                        );
                        $DATA[] = $output;
                    }
                }

            break;

            case 'ekleme_veri_al':
                if( in_array( Aktiviteler::VARYANTLAR_DT, $KULLANICI_IZINLER ) ) {
                    $query = DB::getInstance()->query("SELECT * FROM " . DBT_VARYANTLAR . " WHERE parent IS NULL")->results();
                    foreach ($query as $varyant ) {
                        $color = GitasDT_CSS::$C_BEYAZ;
                        $output = array(
                            "data_id" => $varyant["gid"],
                            "ico" => GitasDT_CSS::$ICO_VARYANT, // js de tanimli
                            "bigtitle" => $varyant["isim"],
                            "subtitle" => "",
                            "color" => $color,
                            "font" => GitasDT_CSS::$F_BOLD,
                            "icoset" => GitasDT_CSS::$ICOSET_VARYANT_PG,
                            "part2" => true
                        );
                        $DATA[] = $output;
                    }
                }
            break;

            case 'varyant_ekle':

                $Varyant = new Varyant();
                $Varyant->ekle( $_POST );
                if( !$Varyant->is_ok() ){
                    $OK = 0;
                }
                $TEXT = $Varyant->get_return_text();

            break;


            case 'varyant_veri_al':

                $Varyant = new Varyant( Input::get("varyant") );
                $DATA = $Varyant->alt_varyantlari_listele();

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