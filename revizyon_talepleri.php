<?php
/**
 * Created by PhpStorm.
 * User: Jeppe
 * Date: 02.04.2017
 * Time: 11:59
 */

    require 'inc/init.php';

    $SATICI_FIRMALAR = DB::getInstance()->query("SELECT * FROM " . DBT_STOK_FIRMARLAR )->results();

    $TITLE = "Revizyon Talepleri";
    $AKTIVITE_KOD = Aktiviteler::REVIZYON_TALEPLERI_DT;
    require 'inc/header.php';
?>

<div class="dtable">

    <div class="barkod-arama">
        <input type="text" id="barkod-arama" placeholder="Barkod Arama"/>
    </div>
    <table id="main-table" class="gitas-table">
        <thead>
        <tr>
            <td>REVİZYON TALEPLERİ</td>
        </tr>
        </thead>

        <tbody class="main-tbody">

        </tbody>

    </table>

</div>

<script type="text/template" id="teklif-ekle-template">

    <div class="parca-tipi-popup-form">
        <div class="form">
            <div class="form-notf"></div>
            <form action="" method="POST" id="teklif_ekle" >

                <div class="input-row">
                    <div class="input-container au">
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

                <div class="input-row">
                    <div class="input-container au">
                        <label for="teklif_ekle_aciklama">Açıklama</label>
                        <textarea name="aciklama" id="teklif_ekle_aciklama"></textarea>
                    </div>
                </div>
                <input type="hidden" name="talep_gid" id="talep_gid" />
                <input type="hidden" name="req" value="revizyon_teklifi_ekle" />
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


<script type="text/javascript">

    $(document).ready(function(){

        GitasREQ.revizyon_talepleri_dt(function(res){
            var html = "";
            for( var j = 0; j < res.data.length; j++ ){
                html += init_row( res.data[j] );
            }
            $(".main-tbody").html(html);
            $('table#main-table').DataTable({ "order": [] });
        });

        $(document).on("click", ".sepet", function(){
            Popup.on( $("#teklif-ekle-template").html(), "Teklif Ekle");
            $("#talep_gid").val($(this).parent().parent().parent().parent().attr("data-id") );
        });

        $(document).on("click", ".buyutec", function(){
            window.open( "<?php echo URL_REVIZYON_TALEBI ?>?talep_gid="+$(this).parent().parent().parent().parent().attr("data-id") );
        });

        $(document).on("click", "[data-role='revtamamdetay']", function(){
            window.open( "<?php echo URL_REVIZYON_TALEBI ?>?talep_gid="+$(this).attr("data-id") );
        });

        $(document).on("submit", "#teklif_ekle", function(ev){
            var _form = this;
            if( FormValidation.check( _form ) ){
                GitasREQ.revizyon_teklif_ekle( $(_form).serialize(), function(res){
                    if( res.ok ){
                        _form.reset();
                    } else {
                        FormValidation.show_serverside_errors( res.inputret );
                    }
                    popup_form_error($(_form), res.ok, res.text);
                });
            }
            ev.preventDefault();
        });

        var sf_popup = new GPopup({baslik:"Satıcı Firma Ekle", content:$("script[data-template='satici_firma_form']").html() });
        $(document).on("click", ".satici-firma-ekle", function(){
            sf_popup.on();
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

        $("#barkod-arama").keyup(function(){
            var _val = $(this).val();
            var trs = $("#main-table").find("[data-id]");
            if( _val.trim() == "" ){
                for( var k = 0; k < trs.length; k++ ){
                    css(trs[k], { display:"table-row"});
                }
            } else {
                GitasREQ.revizyon_barkod_arama( $(this).val(), function(res){
                    for( var j = 0; j < res.data.length; j++ ){
                        for( var k = 0; k < trs.length; k++ ){
                            if( $(trs[k]).attr("data-id") != res.data[j] ) hide(trs[k]);
                        }
                    }
                });
            }
        });
    });

</script>


<?php

require 'inc/footer.php';