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
            "header"   => "İSTATİSTİKLER - DETAYLAR",
            "items"    => array(
                array( "key" => "Toplam Değişim", "val" => 15 ),
                array( "key" => "Parça Kullanım Sırası", "val" => 7 ),
                array( "key" => "En Çok Değişen", "val" => "34 YG 3831" ),
                array( "key" => "İdeal Değişim Yüzdesi", "val" => "%69" ),
                array( "key" => "İdeal Değişim KM", "val" => "34500  - 40000"  ),
                array( "key" => "İdefal Değişim Ay", "val" => "14 - 16" )
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


    <script type="text/template" id="otobus_degisim_plan_tema">

        <div class="parca-degisim-plan">

            <div class="parca-section">
                <span>Sağ Ön</span>
                <ul>
                    <li>
                        <div class="ust">
                            <div class="sol">4. Değişim</div>
                            <div class="sag bok">İdeal değişim aralığında değil! ( Erken )</div>
                        </div>
                        <ul class="alt">

                            <li>Tarih: 2017-04-13 14:54</li>
                            <li>Geçen Süre: 9 Ay 25 Gün</li>
                            <li>KM: 8383123</li>
                            <li>Fark: 3493</li>

                        </ul>
                    </li>
                    <li>
                        <div class="ust">
                            <div class="sol">3. Değişim</div>
                            <div class="sag ok">İdeal değişim aralığında!</div>
                        </div>
                        <ul class="alt">

                            <li>Tarih: 2017-04-13 14:54</li>
                            <li>Geçen Süre: 9 Ay 25 Gün</li>
                            <li>KM: 8383123</li>
                            <li>Fark: 3493</li>

                        </ul>
                    </li>
                </ul>
            </div>

            <div class="parca-section">
                <span>Sağ Arka</span>
                <ul>
                    <li>
                        <div class="ust">
                            <div class="sol">4. Değişim</div>
                            <div class="sag bok">İdeal değişim aralığında değil! ( Erken )</div>
                        </div>
                        <ul class="alt">

                            <li>Tarih: 2017-04-13 14:54</li>
                            <li>Geçen Süre: 9 Ay 25 Gün</li>
                            <li>KM: 8383123</li>
                            <li>Fark: 3493</li>

                        </ul>
                    </li>
                    <li>
                        <div class="ust">
                            <div class="sol">3. Değişim</div>
                            <div class="sag ok">İdeal değişim aralığında!</div>
                        </div>
                        <ul class="alt">

                            <li>Tarih: 2017-04-13 14:54</li>
                            <li>Geçen Süre: 9 Ay 25 Gün</li>
                            <li>KM: 8383123</li>
                            <li>Fark: 3493</li>

                        </ul>
                    </li>
                </ul>
            </div>

        </div>

    </script>


    <script type="text/javascript">


        var PATIP = "<?php echo $Parca_Tipi->get_details("gid" ) ?>";

        var Parca_Tipi = {
            GID: "<?php echo $Parca_Tipi->get_details("gid" ) ?>",
            IDEAL_DEGISIM_KM_ALT: <?php echo $Parca_Tipi->get_details("ideal_degisim_sikligi_alt" ) ?>,
            IDEAL_DEGISIM_KM_UST: <?php echo $Parca_Tipi->get_details("ideal_degisim_sikligi_ust" ) ?>,
            IDEAL_DEGISIM_TARIH_UST: <?php echo $Parca_Tipi->get_details("ideal_degisim_sikligi_tarih_ust" ) ?>,
            IDEAL_DEGISIM_TARIH_ALT: <?php echo $Parca_Tipi->get_details("ideal_degisim_sikligi_tarih_alt" ) ?>
        };

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


        var Degisim_Plan = {
            data: {},
            init: function( data ){
                this.data = data;
                if( this.data["barkodsuz"] != undefined ){
                    return this.init_barkodsuz();
                } else {
                    return this.init_barkodlu();
                }
            },
            init_barkodlu: function(){
                var html = '<div class="parca-degisim-plan">'+
                    '<div class="parca-section">'+
                    '<span></span>'+
                    '<ul>';
                var km_fark = 0, ay_fark = 0, degisim_uyari_html = "", tarih_1, tarih_2;
                for( var x = 0; x < this.data.length; x++ ){
                    km_fark = 0;
                    ay_fark = 0;
                    degisim_uyari_html = "";
                    if( this.data[x+1] != undefined ) {
                        km_fark = this.data[x].km - this.data[x+1].km;
                        if( km_fark >= Parca_Tipi.IDEAL_DEGISIM_KM_UST && km_fark <= Parca_Tipi.IDEAL_DEGISIM_KM_ALT ){
                            degisim_uyari_html = '<div class="sag ok">İdeal değişim aralığında!</div>';
                        } else {
                            degisim_uyari_html = '<div class="sag bok">İdeal değişim aralığında değil!</div>';
                        }
                        tarih_1 = new Date( this.data[x].tarih);
                        tarih_2 = new Date( this.data[x+1].tarih);
                        ay_fark = (( tarih_1.getTime() - tarih_2.getTime() ) / 1000 / 12960000).toFixed(3); // ay
                        // km sinirlarin icindeyse ama tarih degilse kontrol ediyoruz
                        if( !(ay_fark >= Parca_Tipi.IDEAL_DEGISIM_TARIH_UST && ay_fark >= Parca_Tipi.IDEAL_DEGISIM_TARIH_ALT) ){
                            degisim_uyari_html = '<div class="sag bok">İdeal değişim aralığında değil!</div>';
                        }
                    }
                    html += '<li>'+
                        '<div class="ust">'+
                        '<div class="sol">'+(  this.data.length - x ) +'. Değişim</div>'+
                        degisim_uyari_html +
                        '</div>'+
                        '<ul class="alt">'+
                        '<li>Tarih: '+this.data[x].tarih+'</li>'+
                        '<li>Geçen Süre: '+ay_fark+' Ay</li>'+
                        '<li>KM: '+this.data[x].km+'</li>'+
                        '<li>Fark: '+km_fark+'</li>'+
                        '</ul>'+
                        '</li>';
                }
                return html;
            },
            init_barkodsuz: function(){
                var html = '<div class="parca-degisim-plan">';
                for( var aciklama in this.data ){
                    // barkodlu - barkodsuz ayrim yaptigimiz elemani iplemiyoruz
                    if( aciklama == 'barkodsuz' ) continue;
                    html += '<div class="parca-section">'+
                            '<span class="parca-section-toggle">'+aciklama+'</span>'+
                            '<ul class="hidden">';
                    var km_fark = 0, ay_fark = 0, degisim_uyari_html = "", tarih_1, tarih_2;
                    for( var x = 0; x < this.data[aciklama].length; x++ ){
                        km_fark = 0;
                        degisim_uyari_html = "";
                        if( this.data[aciklama][x+1] != undefined ) {
                            km_fark = this.data[aciklama][x].km - this.data[aciklama][x+1].km;
                            if( km_fark >= Parca_Tipi.IDEAL_DEGISIM_KM_UST && km_fark <= Parca_Tipi.IDEAL_DEGISIM_KM_ALT ){
                                degisim_uyari_html = '<div class="sag ok">İdeal değişim aralığında!</div>';
                            } else {
                                degisim_uyari_html = '<div class="sag bok">İdeal değişim aralığında değil!</div>';
                            }
                            tarih_1 = new Date( this.data[aciklama][x].tarih);
                            tarih_2 = new Date( this.data[aciklama][x+1].tarih);
                            ay_fark = (( tarih_1.getTime() - tarih_2.getTime() ) / 1000 / 12960000).toFixed(3); // ay
                            // km sinirlarin icindeyse ama tarih degilse kontrol ediyoruz
                            if( !(ay_fark >= Parca_Tipi.IDEAL_DEGISIM_TARIH_UST && ay_fark >= Parca_Tipi.IDEAL_DEGISIM_TARIH_ALT) ){
                                degisim_uyari_html = '<div class="sag bok">İdeal değişim aralığında değil!</div>';
                            }
                        }
                        html += '<li>'+
                            '<div class="ust">'+
                            '<div class="sol">'+(  this.data[aciklama].length - x ) +'. Değişim</div>'+
                        degisim_uyari_html +
                        '</div>'+
                        '<ul class="alt hidden">'+
                            '<li>Tarih: '+this.data[aciklama][x].tarih+'</li>'+
                            '<li>Geçen Süre: '+ay_fark+' Ay</li>'+
                            '<li>KM: '+this.data[aciklama][x].km+'</li>'+
                            '<li>Fark: '+km_fark+'</li>'+
                        '</ul>'+
                        '</li>';
                    }
                    html += '</ul></div>';
                }
                return html;
            },
            template: function(){

            }
        };

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
                    console.log(res.data);
                    Popup.on(res.data, "Parça Giriş Detay");
                });
            });

            $(document).on("click", "[data-role='cikisdetay']", function(){
                GitasREQ.is_emri_formu_detay($(this).attr("data-id"), function(res){
                    console.log(res.data);
                    Popup.on(res.data, "İş Emri Formu Detay");
                });
            });

            $(document).on("click", "[data-role='otobusdetay'] .arti", function(){
                var _this = $(this),
                    parent = _this.parent().parent().parent().parent().parent(),
                    part2 = parent.find(".part2"),
                    plaka = parent.attr("data-id");
                _this.toggleClass("eksi");
                if( part2.attr("veri-alindi") == "true" ){
                    part2.toggleClass("hidden");
                } else {
                    GitasREQ.parca_tipi_otobus_degisim_plan( PATIP, plaka, function(res){
                        console.log(res);
                        part2.html(Degisim_Plan.init( res.data ) );
                        part2.fadeIn();
                        part2.attr("veri-alindi", true);
                    });
                }

            });

            $(document).on("click", ".parca-section-toggle", function(){
                $(this).parent().find("ul").toggleClass("hidden");
            });

        });
    </script>




<?php
    require 'inc/footer.php';
