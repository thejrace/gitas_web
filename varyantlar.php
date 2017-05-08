<?php
/**
 * Created by PhpStorm.
 * User: Jeppe
 * Date: 02.04.2017
 * Time: 11:59
 */

    require 'inc/init.php';

    $TITLE = "Varyantlar";
    $AKTIVITE_KOD = Aktiviteler::VARYANTLAR_DT;
    require 'inc/header.php';
?>


    <div class="top-nav" style="text-align:center; padding:10px 0">
        <button type="button" class="mnbtn mor varyant-ekle-btn">VARYANT EKLE</button>
    </div>
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
    <script type="text/template" id="varyant_ekle_form">
        <div class="popup-form">
            <div class="form">
                <div class="form-notf"></div>
                <form action="" method="POST" id="varyant_ekle_form">
                    <div class="input-row">
                        <div class="input-container au">
                            <label for="varyant_ekle_form_isim">İsim</label>
                            <input type="text" id="varyant_ekle_form_isim" name="isim" class="req" />
                        </div>
                    </div>
                    <input type="hidden" name="req" value="varyant_ekle"/>
                    <div class="input-row"><button class="mnbtn mor">EKLE</button></div>
                </form>
            </div>
        </div>
    </script>
    <script type="text/javascript">


        $(document).ready(function(){

            GitasREQ.varyantlar_dt( function(res){
                var html = "";
                for( var j = 0; j < res.data.length; j++ ){
                    html += init_row( res.data[j] );
                }
                $(".main-tbody").html(html);
                $('table#main-table').DataTable();
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
                        part2.html(init_varyant_minitable(res.data, false));
                        part2.fadeIn();
                        parent.find(".minitable").DataTable();
                        part2.attr("veri-alindi", "true");
                    });
                }
            });

            $(".varyant-ekle-btn").click(function(){
                Popup.on($("#varyant_ekle_form").html(), "Varyant Ekle");
            });

            $(document).on("click", ".editmor", function(){
                var _this = $(this),
                    parent = _this.parent().parent().parent().parent().parent();
                Popup.on($("#varyant_ekle_form").html(), "'"+ parent.attr("data-key") + "' Alt Varyant Ekle");
                $("#varyant_ekle_form").append("<input type='hidden' name='parent' value='"+parent.attr("data-id")+"' />");

            });

            $(document).on("submit", "#varyant_ekle_form", function(event){
                var form = this;
                if( FormValidation.check( this) ){
                    GitasREQ.varyant_ekle( $(form).serialize(), function(res){
                        if( res.ok ){
                            form.reset();
                            popup_form_error($(form), res.ok, res.text);
                        }
                    });
                }
                event.preventDefault();
            });


        });
    </script>




<?php
    require 'inc/footer.php';
