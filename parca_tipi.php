<?php
/**
 * Created by PhpStorm.
 * User: Jeppe
 * Date: 02.04.2017
 * Time: 11:59
 */

    require 'inc/init.php';

    $Parca_Tipi = new Parca_Tipi(Input::get("psk"));
    if( !$Parca_Tipi->exists() ) exit;

    $statdata = array(
        array(
            "header"   => "İSTATİSTİKLER",
            "items"    => array(
                array( "key" => "Toplam Değişim", "val" => 15 ),
                array( "key" => "Parça Kullanım Sırası", "val" => 7 ),
                array( "key" => "En Çok Değişen", "val" => "34 YG 3831" ),
                array( "key" => "İdeal Değişim Yüzdesi", "val" => "%69" ),
                array( "key" => "Favori Sürücü", "val" => "Veli Konstantin" )
            )
        )
    );


    $TITLE = $Parca_Tipi->get_details("isim") . " Parça Tipi Detayları";
    require 'inc/header.php';
?>


    <div class="section">

        <div class="info-header">
            <?php echo Popup_Stats::init( $statdata, Popup_Stats::$OFF_POPUP ); ?>
        </div>

        <div class="tab full float parca-tab">
            <ul class="tab-bullets clearfix">
                <li><button type="button" class="tab-button mortabbtn girisler-init" data-alindi="false"> GİRİŞLER</button></li>
                <li><button type="button" class="tab-button mortabbtn cikislar-init" data-alindi="false" >ÇIKIŞLAR</button></li>
                <li><button type="button" class="tab-button mortabbtn otobus-init" data-alindi="false" >OTOBÜS</button></li>
                <li><button type="button" class="tab-button mortabbtn surucu-init" data-alindi="false">SÜRÜCÜ</button></li>
            </ul>
            <ul class="tab-divs">
                <li tabdiv="girisler">
                    <div class="tab-item girisler">
                        <div class="dtable">
                            <table id="girisler-table" class="gitas-table">
                                <thead>
                                <tr>
                                    <td>Girişler</td>
                                </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </li>
                <li tabdiv="cikislar">
                    <div class="tab-item cikislar">
                        <div class="dtable">
                            <table id="cikislar-table" class="gitas-table">
                                <thead>
                                <tr>
                                    <td>Çıkışlar</td>
                                </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </li>
                <li tabdiv="cikislar">
                    <div class="tab-item otobus">
                        <div class="dtable">
                            <table id="otobus-table" class="gitas-table">
                                <thead>
                                <tr>
                                    <td>Otobüslere Göre Değişim</td>
                                </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </li>
                <li tabdiv="cikislar">
                    <div class="tab-item surucu">
                        <div class="dtable">
                            <table id="surucu-table" class="gitas-table">
                                <thead>
                                <tr>
                                    <td>Sürücülere Göre Değişim</td>
                                </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </li>
            </ul>
        </div>

    </div>



    <script type="text/javascript">

        var PATIP = "<?php echo $Parca_Tipi->get_details("gid" ) ?>";

        function table_init( btn, req, table ){
            if( btn.attr("data-alindi") == "false" ){
                GitasREQ.parca_tipi_istatistik( req, PATIP, function(res){
                    var html = "";
                    for( var j = 0; j < res.data.length; j++ ){
                        html += init_row( res.data[j] );
                    }
                    $(table + " tbody").html(html);
                    $(table).DataTable({ "order": [] });
                    btn.attr("data-alindi", "true");
                });
            }
        }

        $(document).ready(function(){

            var main_tab = new jwTab( { container: $AHC("parca-tab") } );
            main_tab.init();

            Loader.on();
            table_init( $(".girisler-init"), "girisleri_listele", "#girisler-table");

            $(".cikislar-init").click( function(){
                table_init( $(this), "cikislari_listele", "#cikislar-table");
            });

            $(".surucu-init").click( function(){
                table_init( $(this), "surucu_istatistik", "#surucu-table");
            });

            $(".otobus-init").click( function(){
                table_init( $(this), "otobus_istatistik", "#otobus-table");
            });

            $(document).on("click", "[data-role='girisdetay']", function(){
                GitasREQ.parca_giris_detay($(this).attr("data-id"), function(res){
                    Popup.on(res.data, "Parça Giriş Detay");
                });


            });

        });
    </script>




<?php
    require 'inc/footer.php';
