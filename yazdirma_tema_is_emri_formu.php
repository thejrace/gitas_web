<?php
/**
 * Created by PhpStorm.
 * User: Jeppe
 * Date: 23.04.2017
 * Time: 12:28
 */

    require 'inc/init.php';

    $Form = new Is_Emri_Formu($_GET["form_gid"]);
    if( !$Form->exists() ) exit;
    $FORM_DETAYLAR = $Form->get_details();
    $PARCALAR = $Form->detay_html();

    $Surucu = new Personel( $FORM_DETAYLAR["surucu"] );
    $Yazan = new Personel( $FORM_DETAYLAR["giris_yapan"] );


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
    <title>İŞ EMRİ FORMU YAZDIR</title>

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


<div class="ileri-geri-cont">
    <button type="button" id="on-arka">İKİNCİ SAYFA</button>
</div>

<div class="on-sayfa">
    <div class="tarih-row"><?php echo Common::get_current_datetime() ?></div>
    <div class="ief-gid"><?php echo $FORM_DETAYLAR["gid"] ?></div>
    <div class="ief-barkod"></div>
    <div class="row-1">
        <div class="sol">
            <div><?php echo $FORM_DETAYLAR["plaka"] ?></div>
            <div></div>
            <div><?php echo $FORM_DETAYLAR["aktif_kapi_no"] ?></div>
            <div><?php echo $FORM_DETAYLAR["gelis_km"] ?></div>
            <div><?php echo $FORM_DETAYLAR["gelis_tarih"] ?></div>
            <div><?php echo $FORM_DETAYLAR["cikis_tarih"] ?></div>
            <div><?php echo $Surucu->get_details("isim") ?></div>
            <div><?php echo $Yazan->get_details("isim") ?></div>
        </div>
        <div class="sag">
            <?php echo $FORM_DETAYLAR["sikayet"] ?>
        </div>
    </div>
    <div class="row-2 ariza_tespit">
        <?php echo $FORM_DETAYLAR["ariza_tespit"] ?>
    </div>
    <div class="row-3 yapilan_onarim">
        <?php echo $FORM_DETAYLAR["yapilan_onarim"] ?>
    </div>
    <div class="row-4 kullanilan_malzemeler">
        <?php echo $PARCALAR  ?>
    </div>

</div>

<div class="arka-sayfa">
    <div class="personel-detay">
    </div>
</div>

<script type="text/javascript">

    var aktif_sayfa = 1;

    $(document).ready(function(){

        $(".stat-section").hide();
        $(".yazdirbtn").hide();

        var titles = $("td[title]"), item;
        for( var k = 0; k < titles.length; k++ ){
            item = $(titles[k]);
            item.html( item.attr("title") );
            item.addClass("stok-kodu-td");
        }


        var ekleme_tt  = $(".on-sayfa").find(".buyutec");
        $(".arka-sayfa").find(".buyutec").hide();
        console.log(ekleme_tt);
        for( var j = 0; j < ekleme_tt.length; j++ ){
            item = $(ekleme_tt[j]);
            item.hide();
            item.parent().html( item.attr("ttdata"));
        }


        $("#on-arka").click(function(){
            if( aktif_sayfa == 1 ){
                $(".on-sayfa").hide();
                $(".arka-sayfa").show();
                $(this).html("BİRİNCİ SAYFA");
                aktif_sayfa = 2;
            } else {
                $(".arka-sayfa").hide();
                $(".on-sayfa").show();
                $(this).html("İKİNCİ SAYFA");
                aktif_sayfa = 1;
            }
        });

        var detay_html = $(".input-container");
        var ref_html = $(detay_html[2]).html(),
            pdetay = $(".personel-detay");
        pdetay.html( ref_html );
        $(detay_html[2]).html("");

        pdetay.find("label").html("");
        pdetay.find("thead").html("");
        var trs = pdetay.find("tr"), tds;
        for( var j = 0; j < trs.length; j++ ){
            tds = $(trs[j]).find("td");
            $(tds[2]).append( " / " + $(tds[3]).html() );
            $(tds[3]).html("");
            console.log();
        }

    });

</script>

</body>
</html>

