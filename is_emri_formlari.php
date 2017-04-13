<?php
/**
 * Created by PhpStorm.
 * User: Jeppe
 * Date: 02.04.2017
 * Time: 11:59
 */

    require 'inc/init.php';

    $FILTER = GET_Filter::jsout($_GET);

    $TITLE = "İş Emri Formları";
    require 'inc/header.php';
?>

    <div class="dtable">

        <table id="main-table" class="gitas-table">
            <thead>
            <tr>
                <td>İŞ EMRİ FORMLARI</td>
            </tr>
            </thead>

            <tbody class="main-tbody">

            </tbody>

        </table>



    </div>
    <script type="text/javascript">
        var FILTER = "<?php echo $FILTER; ?>";

        $(document).ready(function(){

            GitasREQ.is_emri_formlari_dt( FILTER, function(res){
                var html = "";
                for( var j = 0; j < res.data.length; j++ ){
                    html += init_row( res.data[j] );
                }
                $(".main-tbody").html(html);
                $('table#main-table').DataTable();
            });


            $(document).on("click", "[data-role='formdetay']", function(){
                GitasREQ.is_emri_formu_detay( $(this).attr("data-id"), function(res){ Popup.on(res.data, "Form Detay"); } );
            });
        });
    </script>




<?php
    require 'inc/footer.php';
