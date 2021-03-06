<?php
/**
 * Created by PhpStorm.
 * User: Jeppe
 * Date: 23.04.2017
 * Time: 12:28
 */

    require 'inc/init.php';



    include "qr/qrlib.php";

    $Form = new Is_Emri_Formu($_GET["form_gid"]);
    if( !$Form->exists() ) exit;
    $FORM_DETAYLAR = $Form->get_details();

    $CIKANLAR = array();
    foreach( $Form->form_cikanlari_listele() as $cikan ){
        $Parca = new Parca( $cikan["stok_kodu"] );
        $varyant = "";

        if( !isset($cikan["varyant_gid"] ) && !isset($cikan["parent_varyant"] ) ) {
            $Ana_Varyant = new Varyant( $Parca->get_details("varyant_gid") );
            $varyant = $Ana_Varyant->get_details("isim");
        } else {
            if( isset($cikan["parent_varyant"]) ){
                $Ana_Varyant = new Varyant($cikan["parent_varyant"] );
                $varyant = $Ana_Varyant->get_details("isim");
            }
            if( isset($cikan["varyant_gid"])){
                $Alt_Varyant = new Varyant( $cikan["varyant_gid"] );
                $varyant .= " - " . $Alt_Varyant->get_details("isim");
            }
        }

        $Parca_Tipi = new Parca_Tipi( $Parca->get_details("parca_tipi"));
        if( $Parca_Tipi->get_details("tip") == Parca_Tipi::$BARKODLU ){


            $CIKANLAR[] = QR_Output::olustur( $cikan["stok_kodu"], $Parca_Tipi->get_details("isim"), $varyant );
        }
    }

?>

<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"> <!-- IE render en son versiyona gore -->
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="<?php echo URL_RES_CSS ?>ief.css" />
    <script type="text/javascript" src="<?php echo URL_RES_JS ?>common.js"></script>
    <script type="text/javascript" src="<?php echo URL_RES_JS ?>jquery.js"></script>
    <script type="text/javascript" src="<?php echo URL_RES_JS ?>main.js"></script>
    <title>İŞ EMRİ FORMU ÇIKANLAR YAZDIR</title>

</head>
<style type="text/css" media="print">
    @page {
        size: auto;
        margin: 0;
    }
    .ileri-geri-cont {
        display:none;
    }
</style>
<body>



<div class="on-sayfa">

    <?php

        foreach( $CIKANLAR as $cikan_qr ){

            echo '<img src="'.$cikan_qr.'" />';
        }


    ?>

</div>


<script type="text/javascript">

    $(document).ready(function(){


    });

</script>

</body>
</html>

