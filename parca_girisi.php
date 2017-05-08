<?php
/**
 * Created by PhpStorm.
 * User: Jeppe
 * Date: 21.03.2017
 * Time: 15:23
 */

    require 'inc/init.php';


    $PARCA_TIPLERI = DB::getInstance()->query("SELECT * FROM " . DBT_PARCA_TIPLERI )->results();
    $SATICI_FIRMALAR = DB::getInstance()->query("SELECT * FROM " . DBT_STOK_FIRMARLAR )->results();


    $Parca_Giris = new Parca_Girisi();
    $Parca_Giris->temp_id_olustur();
    $PGID = $Parca_Giris->get_details("gid");

    $TITLE = "Parça Girişi";
    $AKTIVITE_KOD = Aktiviteler::PARCA_GIRISI;
    require 'inc/header.php';
?>


    <div class="section">
        <div class="section-header">
            <label>Form</label>
        </div>
        <div class="section-content">

            <div class="section-form">
                <div class="form">
                    <form action="" method="post" id="parca_ekle">
                        <div class="form-row">
                            <div class="form-col ">
                                <div class="binput-container">
                                    <label for="parca_tipi">Parça Tipi</label>
                                    <select name="parca_tipi" id="parca_tipi" class="select_no_zero uzun">
                                        <option value="0">Seçiniz...</option>
                                        <?php
                                        foreach( $PARCA_TIPLERI as $tip ){
                                            $Parca_Tipi = new Parca_Tipi( $tip["gid"] );
                                            echo "<option value='" . $Parca_Tipi->get_details("gid") . "'>" . $Parca_Tipi->get_details("isim") . "</option>";
                                        }
                                        ?>
                                    </select>
                                    <button type="button" class="input-button parca-tipi-ekle-btn" title="Parça Tipi Ekle">+</button>
                                </div>
                            </div>

                            <div class="form-col varyant-append">

                            </div>

                            <div class="form-col">
                                <div class="binput-container">
                                    <label for="aciklama">Açıklama</label>
                                    <input type="text" name="aciklama" id="aciklama" />
                                </div>
                            </div>


                        </div>

                        <div class="form-row dinamik">
                            <div class="form-col">
                                <div class="binput-container">
                                    <label for="satici_firma">Satıcı Firma</label>
                                    <select name="satici_firma" id="satici_firma" class="select_no_zero uzun">
                                       <option value="0">Seçiniz..</option>
                                        <?php
                                        foreach( $SATICI_FIRMALAR as $tip ){
                                            $Firma = new Satici_Firma( $tip["gid"] );
                                            echo "<option value='" . $Firma->get_details("gid") . "'>" . $Firma->get_details("isim") . "</option>";
                                        }
                                        ?>
                                        </select>
                                    <button type="button" class="input-button satici-firma-ekle" title="Satıcı Firma Ekle">+</button>
                                   </div>
                                </div>
                            <div class="form-col">
                                <div class="binput-container">
                                    <label for="fatura_no">Fatura No</label>
                                    <input type="text" class="kisa req posnum" name="fatura_no" id="fatura_no" />
                                </div>
                           </div>
                            <div class="form-col">
                                <div class="binput-container">
                                    <label for="adet">Adet</label>
                                    <input type="text" class="kisa req posnum" name="adet" id="adet" value="1" />
                                </div>
                            </div>
                        </div>

                        <div class="form-row garanti">
                            <div class="form-col garanti">
                                <div class="binput-container">
                                    <label for="garanti_baslangic">Garanti Başlangıç</label>
                                    <input type="text" name="garanti_baslangic" id="garanti_baslangic" />
                                </div>
                            </div>
                            <div class="form-col garanti">
                                <div class="binput-container">
                                    <label for="garanti_suresi">Garanti Süresi</label>
                                    <input type="text" name="garanti_suresi" class="posnum" id="garanti_suresi" />
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <input type="hidden" name="req" value="parca_girisi" />
                            <input type="hidden" name="parca_giris_gid" value="<?php echo $PGID ?>" />
                            <button class="mnbtn mor">EKLE</button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>

    <div class="section">
        <div class="section-header">
            <label>Eklenenler</label>
        </div>
        <div class="section-content">
            <div class="dtcontent ">
                <ul class="eklenenler">



                </ul>
            </div>
        </div>
    </div>

    <script type="text/template" data-template="parca_tipi_form">
        <div class="parca-tipi-popup-form">
            <div class="form">
                <div class="form-notf"></div>
                <form action="" method="POST" class="parca-tipi-ekle" id="parca_tipi_ekle">
                    <div class="input-row">
                        <div class="binput-container">
                            <label for="isim">İsim</label>
                            <input type="text" id="parca_tipi_ekle_isim" name="isim" class="req" />
                        </div>
                    </div>

                    <div class="input-row">
                        <div class="binput-container">
                            <label for="tip">Tip</label>
                            <select id="tipparca_tipi_ekle_" name="tip" class="parca-tip-select select_no_zero uzun">
                                <option value="0">Seçiniz..</option>
                                <option value="1">Barkodlu</option>
                                <option value="2">Barkodsuz</option>
                            </select>
                        </div>
                    </div>

                    <div class="input-row">
                        <button type="button" class="mnbtn mor varyant-ekle-btn" title="Varyant ekle">VARYANT</button>
                    </div>

                    <div class="input-row">
                        <div class="binput-container">
                            <label for="parca_tipi_ekle_kategori">Kategori</label>
                            <select id="parca_tipi_ekle_kategori" name="kategori" class="select_no_zero uzun">
                                <option value="0">Seçiniz..</option>
                                <option value="1">Mekanik</option>
                                <option value="2">Elektronik</option>
                                <option value="3">Sarf</option>
                                <option value="4">İç Trim</option>
                                <option value="5">Dış Trim</option>
                            </select>
                        </div>
                    </div>

                    <div class="input-row">
                        <div class="binput-container">
                            <label for="miktar_olcu_birimi">Miktar Ölçü Birimi</label>
                            <select id="parca_tipi_ekle_parca_tipi_ekle_miktar_olcu_birimi" name="miktar_olcu_birimi" class="select_no_zero uzun">
                                <option value="0">Seçiniz..</option>
                                <option value="Adet">Adet</option>
                                <option value="Litre">Litre</option>
                                <option value="Kilogrma">Kilogram</option>
                            </select>
                        </div>
                    </div>

                    <div class="input-row">
                        <div class="binput-container">
                            <label for="parca_tipi_ekle_ideal_degisim_sikligi_alt">İdeal Değişim Sıklığı KM</label>
                            <input type="text" id="parca_tipi_ekle_ideal_degisim_sikligi_alt" name="ideal_degisim_sikligi_alt" class="kisa posnum" value="0" />
                            <input type="text" id="parca_tipi_ekle_ideal_degisim_sikligi_ust" name="ideal_degisim_sikligi_ust" class="kisa posnum" value="0" />
                        </div>
                    </div>
                    <div class="input-row">
                        <div class="binput-container">
                            <label for="parca_tipi_ekle_ideal_degisim_sikligi_tarih_alt">İdeal Değişim Sıklığı Ay</label>
                            <input type="text" id="parca_tipi_ekle_ideal_degisim_sikligi_tarih_alt" name="ideal_degisim_sikligi_tarih_alt" class="kisa posnum" value="0" />
                            <input type="text" id="parca_tipi_ekle_ideal_degisim_sikligi_tarih_ust" name="ideal_degisim_sikligi_tarih_ust" class="kisa posnum" value="0" />
                        </div>
                    </div>
                    <div class="input-row">
                        <div class="binput-container">
                            <label for="parca_tipi_ekle_kritik_seviye_limiti">Kritik Seviye Limiti</label>
                            <input type="text" id="parca_tipi_ekle_kritik_seviye_limiti" name="kritik_seviye_limiti" class="kisa posnum" value="0" />
                        </div>
                    </div>
                    <input type="hidden" name="req" value="parca_tipi_ekle" />
                    <button class="mnbtn mor">EKLE</button>
                </form>
            </div>
        </div>
    </script>

    <script type="text/template" data-template="satici_firma_form">
        <div class="parca-tipi-popup-form">
            <div class="form">
                <div class="form-notf"></div>
                <form action="" method="POST" class="satici-firma-ekle" >
                    <div class="input-row">
                        <div class="binput-container">
                            <label for="isim">Firma Adı</label>
                            <input type="text" id="isim" name="isim" class="req" />
                        </div>
                    </div>

                    <div class="input-row">
                        <div class="binput-container">
                            <label for="satici_firma_ekle_vergi_dairesi">Vergi Dairesi</label>
                            <input type="text" id="satici_firma_ekle_vergi_dairesi" name="vergi_dairesi" class="req" />
                        </div>
                    </div>

                    <div class="input-row">
                        <div class="binput-container">
                            <label for="satici_firma_ekle_vergi_no">Vergi No</label>
                            <input type="text" id="satici_firma_ekle_vergi_no" name="vergi_no" class="req" />
                        </div>
                    </div>

                    <div class="input-row">
                        <div class="binput-container">
                            <label for="satici_firma_ekle_telefon_1">Telefon 1</label>
                            <input type="text" id="satici_firma_ekle_telefon_1" name="telefon_1" class="req posnum" />
                        </div>
                    </div>

                    <div class="input-row">
                        <div class="binput-container">
                            <label for="satici_firma_ekle_telefon_2">Telefon 2</label>
                            <input type="text" id="satici_firma_ekle_telefon_2" name="telefon_2" class="posnum" />
                        </div>
                    </div>

                    <div class="input-row">
                        <div class="binput-container">
                            <label for="satici_firma_ekle_eposta">Eposta</label>
                            <input type="email" id="satici_firma_ekle_eposta" name="eposta" class="email"/>
                        </div>
                    </div>
                    <div class="input-row">
                        <div class="binput-container">
                            <label for="satici_firma_ekle_aciklama">Notlar</label>
                            <textarea name="aciklama" id="satici_firma_ekle_aciklama"></textarea>
                        </div>
                    </div>

                    <input type="hidden" name="req" value="satici_firma_ekle" />
                    <button class="mnbtn mor">EKLE</button>
                </form>
            </div>
        </div>
    </script>


    <script type="text/html" id="varyant-table">
        <div class="dtable">
            <table id="main-table" class="gitas-table">
                <thead>
                <tr>
                    <td>VARYANT</td>
                </tr>
                </thead>
                <tbody class="main-tbody">
                </tbody>
            </table>
        </div>
    </script>
    <script type="text/javascript">
        var PTIP_TANIMLI_VARYANTLAR = {};

        $(document).ready(function(){

            $(document).on( "submit", ".parca-tipi-ekle", function(event){
                var form = this, notf = $(this).parent().find(".form-notf"), select = $("#parca_tipi");
                if( FormValidation.check( form ) ){
                    var data = $(this).serialize();
                    GitasREQ.parca_tipi_ekle( data, function(res){
                        if( res.ok ){
                            select.append("<option value='"+res.data.gid+"'>"+res.data.isim+"</option>");
                            $AH("parca_tipi").selectedIndex = $AH("parca_tipi").options.length - 1;
                            form.reset();
                            select.trigger("change");
                            PTIP_TANIMLI_VARYANTLAR = {};
                        } else {
                            FormValidation.show_serverside_errors( res.inputret );
                        }
                        popup_form_error( $(form), res.ok, res.text);
                        console.log(res);
                    });
                }
                event.preventDefault();
            });

            $(document).on("click", ".varyant-ekle-btn", function(){
                Popup.on($("#varyant-table").html(), "Varyant Tanımlamaları");
                GitasREQ.varyantlar_ekleme_dt( function(res){
                    var html = "";
                    for( var j = 0; j < res.data.length; j++ ){
                        html += init_row( res.data[j] );
                    }
                    $(".main-tbody").html(html);
                    var table = $('table#main-table'), btn;
                    table.DataTable();
                    for( var key in PTIP_TANIMLI_VARYANTLAR ) {
                        btn = table.find("[data-id='" + key + "']").find(".tickgri");
                        btn.addClass("tickyesil");
                        btn.removeClass("tickgri");
                    }
                });

            });

            $(document).on("click", ".arti", function(){
                var _this = $(this),
                    parent = _this.parent().parent().parent().parent().parent(),
                    part2 = parent.find(".part2"),
                    varyant = parent.attr("data-id");
                _this.toggleClass("eksi");
                if( part2.attr("veri-alindi") == "true" ){
                    part2.toggleClass("hidden");
                } else {
                    GitasREQ.varyant_genislet( varyant, function(res){
                        console.log(res.data);
                        part2.html(init_varyant_minitable(res.data, true));
                        part2.fadeIn();
                        parent.find(".minitable").DataTable();
                        part2.attr("veri-alindi", "true");
                        var btn;
                        for( var key in PTIP_TANIMLI_VARYANTLAR ){
                            btn = part2.find("[data-id='"+key+"']").find(".minitableico");
                            btn.addClass( "tickyesil");
                            btn.removeClass( "tickgri");
                        }
                    });

                }
            });

            $(document).on("click", ".tickyesil, .tickgri", function(){
                var _this = $(this),
                    parent = _this.parent().parent().parent().parent().parent();
                if( parent.attr("data-id") == undefined ) parent = _this.parent().parent();
                if( _this.hasClass("tickyesil") ){
                    _this.removeClass("tickyesil");
                    _this.addClass("tickgri");
                    delete PTIP_TANIMLI_VARYANTLAR[parent.attr("data-id")];
                    remove_elem($("#v_"+parent.attr("data-id")).get(0));
                } else {
                    _this.removeClass("tickgri");
                    _this.addClass("tickyesil");
                    PTIP_TANIMLI_VARYANTLAR[parent.attr("data-id")] = true;
                    $("#parca_tipi_ekle").append("<input type='hidden' name='varyantlar[]' id='v_"+parent.attr("data-id")+"' value='"+parent.attr("data-id")+"' />");
                }
                console.log(PTIP_TANIMLI_VARYANTLAR);
            });


            /*$(document).on("change", ".parca-tip-select", function(){
                var html = "";
                if( $(this).val() == "2" ){
                    html = '<label for="varyant_isim">Varyant</label>'+
                                '<input type="text" id="varyant_isim" />'+
                                '<button type="button" class="input-button varyant-ekle-btn" title="Varyant ekle">+</button>'+
                                '</div>'+
                                '<div class="varyant-listesi"></div>';
                }
                $(".parca-tipi-varyant-cont").html(html);
            });

            $(document).on("click", ".varyant-ekle-btn", function(){
                var input = $AH("varyant_isim");
                if( !FormValidation.custom_check( input, "", function(){
                    return ( input.value.trim() != "" );
                }) ) return;
                $(".varyant-listesi").append('<div class="varyant-item"><input type="hidden" name="varyantlar[]" value="'+input.value+'" /> <span>'+input.value+'</span><button type="button" class="dtbtn dtico carpikirmizi" btn-role="varyant_sil_noajax"></button></div>');
                input.value = "";
            });


            $(document).on("click", "[btn-role='varyant_sil_noajax']", function(){
                remove_elem( this.parentNode );
            });*/

            $("#parca_ekle").submit(function(event){
                if( FormValidation.check( $AH("parca_ekle") ) ){
                    var data = $(this).serialize();
                    console.log(serialize( $AH("parca_ekle") ));
                    GitasREQ.parca_giris_form( data, function(res){
                        if( res.ok ){
                            var parca;
                            for( var j = 0; j < res.data.eklenenler.length; j++ ){
                                parca = res.data.eklenenler[j];
                                if( parca.stok_kodu != undefined ){
                                    $(".eklenenler").prepend('<li class="clearfix">'+
                                        '<div class="content">'+
                                        '<span class="col-ico"><i class="dtico parca"></i></span>'+
                                        '<span class="col-bigtitle">'+parca.tip+' - '+parca.aciklama+' - '+parca.firma+'</span>'+
                                        '<span class="col-subtitle">'+parca.adet+'</span>'+
                                        '</div>'+
                                        '<div class="right-content">'+
                                        '<span class="col-ico"><i class="dtico barkodsari" title="'+parca.stok_kodu+'"></i></span>'+
                                        '</div>'+
                                        '</li>');
                                } else {
                                    $('.eklenenler').prepend('<li class="clearfix">'+
                                        '<div class="content">'+
                                        '<span class="col-ico"><i class="dtico parca"></i></span>'+
                                        '<span class="col-bigtitle">'+parca.tip+' - '+parca.aciklama+' - '+parca.firma+'</span>'+
                                        '<span class="col-subtitle">'+parca.adet+'</span>'+
                                        '</div>'+
                                        '</li>');
                                }
                            }
                        } else {

                        }
                        console.log(res);
                    });
                }
                event.preventDefault();

            });

            var pt_popup = new GPopup({baslik:"Parça Tipi Ekle", content:$("script[data-template='parca_tipi_form']").html() });
            $(".parca-tipi-ekle-btn").click(function(){
                pt_popup.on();
                //Popup.on( $("script[data-template='parca_tipi_form']").html(), "Parça Tipi Ekle");
            });


            var sf_popup = new GPopup({baslik:"Satıcı Firma Ekle", content:$("script[data-template='satici_firma_form']").html() });
            $(document).on("click", ".satici-firma-ekle", function(){
                sf_popup.on();
                //Popup.on( $("script[data-template='satici_firma_form']").html(), "Satıcı Firma Ekle");
            });

            $(document).on( "submit", ".satici-firma-ekle", function(event){
                var form = this, notf = $(this).parent().find(".form-notf"), select = $("#satici_firma");
                if( FormValidation.check( form ) ){
                    var data = $(this).serialize();
                    GitasREQ.satici_firma_ekle( data, function(res){
                        if( res.ok ){
                            form.reset();
                            select.append("<option value='"+res.data.gid+"'>"+res.data.firma_adi+"</option>");
                            select.get(0).selectedIndex = $AH("satici_firma").options.length - 1;
                        } else {
                            FormValidation.show_serverside_errors( res.inputret );
                        }
                        popup_form_error($(form), res.ok, res.text);
                        console.log(res);
                    });
                }
                event.preventDefault();
            });

            $.datetimepicker.setLocale('tr');
            var dtpicker_options_dt = {
                format:'Y-m-d'
            };
            $("#garanti_baslangic").datetimepicker(dtpicker_options_dt);
            $("#parca_tipi").change(function(){
                var val = $("#parca_tipi option:selected").val(),
                    dinamik = $(".dinamik"), varyant_append = $(".varyant-append"), garanti =  $(".garanti");
                if( val.trim() == "0" ){
                    varyant_append.html("");
                    return;
                }


                GitasREQ.parca_tipi_select_giris( val, function(res){
                    console.log(res);
                    varyant_append.html("");
                    if( res.data.varyantlar.length == 0 ) return;
                    var html;
                    html ="<div class='binput-container'><label for='varyant_gid'>Varyant</label><select class='select_no_zero uzun' name='varyant_gid' id='varyant_gid'><option value='0'>Seçiniz..</option>";
                    for( var x = 0; x < res.data.varyantlar.length; x++ ){
                        html += "<option value='"+res.data.varyantlar[x].gid+"'>"+res.data.varyantlar[x].isim+"</option>";
                    }
                    html += "</select></div>";
                    varyant_append.append( html );
                });
                /*GitasREQ.parca_tipi_select( val, function(res){
                    console.log(res);
                    varyant_append.html("");
                    var html;
                    if( res.data.tip == "1"){
                        html ="<div class='binput-container'><label for='aciklama'>Açıklama</label><input type='text' class='req' name='aciklama' id='aciklama'></div>";
                        varyant_append.append( html );
                        dinamik.append(TEMPLATE_BARKODLU);
                    } else {
                        html ="<div class='binput-container'><label for='aciklama'>Açıklama</label><select class='select_no_zero uzun' name='aciklama' id='aciklama'><option value='0'>Seçiniz..</option>";
                        for( var x = 0; x < res.data.varyantlar.length; x++ ){
                            html += "<option value='"+res.data.varyantlar[x].gid+"'>"+res.data.varyantlar[x].isim+"</option>";
                        }
                        html += "</select></div>";
                        varyant_append.append( html );
                        remove_elem( garanti.get(0) );

                    }
                });*/
            });


        });


    </script>


<?php
    require 'inc/footer.php';