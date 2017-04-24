<?php
/**
 * Created by PhpStorm.
 * User: Jeppe
 * Date: 24.04.2017
 * Time: 22:36
 */
    require 'inc/init.php';

    $Talep = new Revizyon_Talebi( Input::get("talep_gid") );
    if( !$Talep->exists() ) die;

    $Form = new Is_Emri_Formu( $Talep->get_details("form_gid") );
    $Parca = new Barkodlu_Parca( $Talep->get_details("stok_kodu") );
    $Parca_Tipi = new Parca_Tipi( $Parca->get_details("tip"));
    $Duzenleyen = new Personel( $Talep->get_details("duzenleyen_personel") );


    $statdata = array(
        array(
            "header"   => "DETAYLAR",
            "items"    => array(
                array( "key" => "GID", "val" => $Talep->get_details("gid" ) ),
                array( "key" => "Form GID", "val" => $Form->get_details("gid" ) ),
                array( "key" => "Parça Tipi", "val" => $Parca_Tipi->get_details("isim") ),
                array( "key" => "Parça Stok Kodu", "val" => $Parca->get_details("stok_kodu") ),
                array( "key" => "Parça Açıklama", "val" => $Parca->get_details("aciklama") ),
                array( "key" => "Çıktığı Araç", "val" => $Form->get_details("plaka") ),
                array( "key" => "Düzenleyen", "val" => $Duzenleyen->get_details("isim") ),
                array( "key" => "Tarih", "val" => $Talep->get_details("tarih") ),
                array( "key" => "Durum", "val" => $Talep->durum_str("tarih") )
            )
        )
    );

    $TITLE = "Revizyon Talep Detayı";
    $AKTIVITE_KOD = Aktiviteler::REVIZYON_TALEP_INCELEME;
    require 'inc/header.php';

?>

    <div class="section">
        <div class="info-header">
            <?php echo Popup_Stats::init( $statdata, Popup_Stats::$OFF_POPUP ); ?>
        </div>
    </div>

    <div class="talep-ust-bar">
        <?php if( $Talep->get_details("durum") == Revizyon_Talebi::$PARCA_BEKLENIYOR ) { ?>
            <button type="button" class="mnbtn mor" id="tamamla">TAMAMLA</button>
        <?php } ?>
    </div>
    <div class="teklifler-cont">

       <?php echo $Talep->teklifler_html() ?>

    </div>

    <script type="text/javascript">

        $(document).ready(function(){

            $(document).on("click", ".onayla", function(){
                GitasREQ.revizyon_teklif_onayla( $(this).parent().attr("data-id"), function(res){
                    if( res.ok ){
                        $(".onayla").hide();
                    }
                });
            });

            $("#tamamla").click(function(){
                GitasREQ.revizyon_talep_tamamla( "<?php echo $Talep->get_details("gid")?>", function(res){
                    if( res.ok ){
                        location.reload();
                    }
                });
            });

        });

    </script>



<?php

    require 'inc/footer.php';