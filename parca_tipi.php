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
                <li><button type="button" class="tab-button mortabbtn">GİRİŞLER</button></li>
                <li><button type="button" class="tab-button mortabbtn cikislar-init" >ÇIKIŞLAR</button></li>
                <li><button type="button" class="tab-button mortabbtn otobus-init" >OTOBÜS</button></li>
                <li><button type="button" class="tab-button mortabbtn surucu-init">SÜRÜCÜ</button></li>
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
                                <tbody class="girisler-tbody">

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
                                <tbody class="cikislar-tbody">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </li>
                <li tabdiv="cikislar">
                    <div class="tab-item otobus">
                        <div class="dtable">
                            <table id="main-table" class="gitas-table">
                                <thead>
                                <tr>
                                    <td>Otobüslere Göre Değişim</td>
                                </tr>
                                </thead>
                                <tbody class="main-tbody">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </li>
                <li tabdiv="cikislar">
                    <div class="tab-item surucu">
                        <div class="dtable">
                            <table id="main-table" class="gitas-table">
                                <thead>
                                <tr>
                                    <td>Sürücülere Göre Değişim</td>
                                </tr>
                                </thead>
                                <tbody class="main-tbody">

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
        $(document).ready(function(){

            var main_tab = new jwTab( { container: $AHC("parca-tab") } );
            main_tab.init();

            Loader.on();
            $.ajax({
                type: "POST",
                url:Gitas.AJAX_URL + "parca_tipi.php",
                dataType: 'json',
                data: { req:"girisleri_listele", patip:PATIP },
                success: function(res){
                    Loader.off();
                    var html = "";
                    for( var j = 0; j < res.data.length; j++ ){
                        html += init_row( res.data[j] );
                    }
                    $(".girisler-tbody").html(html);
                    $('table#girisler-table').DataTable();
                }
            });

            $(".cikislar-init").click( function(){
                var _this = $(this);
                if( _this.attr("data-alindi") == undefined ){
                    Loader.on();
                    $.ajax({
                        type: "POST",
                        url:Gitas.AJAX_URL + "parca_tipi.php",
                        dataType: 'json',
                        data: { req:"cikislari_listele", patip:PATIP },
                        success: function(res){
                            console.log(res.data);
                            Loader.off();
                            var html = "";
                            for( var j = 0; j < res.data.length; j++ ){
                                html += init_row( res.data[j] );
                            }
                            $(".cikislar-tbody").html(html);
                            $('table#cikislar-table').DataTable();
                            _this.attr("data-alindi", "true");
                        }
                    });
                }

            });

        });
    </script>




<?php
    require 'inc/footer.php';
