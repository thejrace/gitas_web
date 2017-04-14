<?php
/**
 * Created by PhpStorm.
 * User: Jeppe
 * Date: 02.04.2017
 * Time: 11:59
 */

    require 'inc/init.php';

    $TITLE = "Stok";
    require 'inc/header.php';
?>



    <div class="dtable">

            <table id="main-table" class="gitas-table">
                <thead>
                    <tr>
                        <td>PARÇA TİPİ</td>
                    </tr>
                </thead>

                <tbody class="main-tbody">

                </tbody>

            </table>



    </div>

    <script type="text/javascript">


        $(document).ready(function(){

            GitasREQ.parca_tipi_dt( function(res){
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
                    ptip = parent.attr("data-id");
                _this.toggleClass("eksi");
                if( part2.attr("veri-alindi") == "true" ){
                    part2.toggleClass("hidden");
                } else {
                    GitasREQ.parca_tipi_genislet( ptip, function(res){
                        console.log(res.data);
                        part2.html(init_stok_minitable(res.data));
                        part2.fadeIn();
                        parent.find(".minitable").DataTable();
                        part2.attr("veri-alindi", "true");
                    });
                }
            });

            $(document).on("click", ".ayarlar", function(){
                var _this = $(this),
                    parent = _this.parent().parent().parent().parent().parent(),
                    ptip = parent.attr("data-id");
                GitasREQ.parca_tipi_ayarlar( ptip, function(res){
                    Popup.on(res.data , parent.attr("data-key") + " Ayarlar");
                });
            });

            $(document).on("click", ".stats", function(){
                var _this = $(this),
                    parent = _this.parent().parent().parent().parent().parent(),
                    ptip = parent.attr("data-id");
                window.open( "<?php echo URL_PARCA_TIPI ?>?psk="+ptip, '_blank');
            });

            $(document).on("submit", "#patip_duzenle", function(event){
                var form = this;
                if( FormValidation.check(this) ){
                    GitasREQ.parca_tipi_ayarlar_submit( $(form).serialize(), function(res){
                        popup_form_error($(form), res.ok, res.text);
                    });
                }
                event.preventDefault();
            });

            $(document).on("click", "[btn-role='mtparcadata']", function(){
                var sk = $(this).parent().parent().attr("data-id");
                GitasREQ.parca_detay( sk, function(res){
                    Popup.on(res.data, "Parça Detay");
                });
            });


        });
    </script>




<?php
    require 'inc/footer.php';
