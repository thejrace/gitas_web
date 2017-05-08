<?php
/**
 * Created by PhpStorm.
 * User: Jeppe
 * Date: 23.04.2017
 * Time: 12:28
 */

    require 'inc/init.php';
    require 'qr/qrlib.php';
    $GIRENLER = array();
    $Giris = new Parca_Girisi( $_GET["pargid"] );
    if( !$Giris->exists() ) exit;
    $Giris->giris_icerik_listele();
    foreach( $Giris->get_details("giris_icerik") as $giris ){
        $Parca = new Parca( $giris["stok_kodu"] );
        $Parca_Tipi = new Parca_Tipi( $Parca->get_details("parca_tipi"));
        if( $Parca_Tipi->get_details("tip") == Parca_Tipi::$BARKODLU ){
            $GIRENLER[] = QR_Output::olustur( $giris["stok_kodu"], $Parca_Tipi->get_details("isim"), $Parca->get_details("aciklama") );
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
    <title>PARÇA GİRİŞİ YAZDIR</title>

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

    foreach( $GIRENLER as $qr ){
        echo '<img src="'.$qr.'" />';
    }


    ?>

</div>

<script type="text/javascript">


    $(document).ready(function(){



    });

</script>

</body>
</html>

