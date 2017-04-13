<?php
/**
 * Created by PhpStorm.
 * User: Jeppe
 * Date: 02.04.2017
 * Time: 11:59
 */

    require 'inc/init.php';

    $TITLE = "Otobüsler";
    require 'inc/header.php';
?>

    <div class="dtable">

        <table id="main-table" class="gitas-table">
            <thead>
            <tr>
                <td>OTOBÜSLER</td>
            </tr>
            </thead>

            <tbody class="main-tbody">

            </tbody>

        </table>



    </div>


    <script type="text/javascript">
        $(document).ready(function(){

            GitasREQ.otobusler_dt( function(res){
                var html = "";
                for( var j = 0; j < res.data.length; j++ ) html += init_row( res.data[j] );
                $(".main-tbody").html(html);
                $('table#main-table').DataTable();
            });



            $(document).on("click", ".parca", function(){
                var fid = $(this).parent().parent().parent().parent().attr("data-id");
                window.open( "<?php echo URL_ISEMRI_FORMLARI ?>?filter_plaka="+fid, '_blank');
            });

            $(document).on("click", ".buyutec", function(){
                var fid = $(this).parent().parent().parent().parent().attr("data-id");
                GitasREQ.otobus_detay( fid, function(res){
                    Popup.on( res.data, fid +" Detay Görüntüle");
                });
            });

            $(document).on("click", ".ayarlar", function(){
                var fid = $(this).parent().parent().parent().parent().attr("data-id");
                GitasREQ.otobus_ayarlar( fid, function(res){
                    Popup.on( res.data, fid +" Ayarlar");
                });
            });

            $(document).on("submit", "#otobus_ayarlar", function(event){
                if( FormValidation.check(this) ){
                    var _this = this;
                    GitasREQ.otobus_ayarlar_submit($(_this).serialize(), function(res){
                        popup_form_error($(_this), res.ok, res.text);
                    });
                }
                event.preventDefault();

            });

            $(document).on("click", ".stats", function(){
                var fid = $(this).parent().parent().parent().parent().attr("data-id");
                GitasREQ.otobus_istatistik( fid, function(res){
                    Popup.on(res.data, fid + " İstatistikler");
                });
            });

        });
    </script>




<?php
    require 'inc/footer.php';
