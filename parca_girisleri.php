<?php
/**
 * Created by PhpStorm.
 * User: Jeppe
 * Date: 02.04.2017
 * Time: 11:59
 */

    require 'inc/init.php';

    $TITLE = "Parça Girişleri";
    $AKTIVITE_KOD = Aktiviteler::PARCA_GIRISLERI_DT;
    require 'inc/header.php';
?>

<div class="dtable">

    <table id="main-table" class="gitas-table">
        <thead>
        <tr>
            <td>PARÇA GİRİŞLERİ</td>
        </tr>
        </thead>

        <tbody class="main-tbody">

        </tbody>

    </table>



</div>


<script type="text/javascript">
    $(document).ready(function(){

        GitasREQ.parca_giris_dt(function(res){
            var html = "";
            for( var j = 0; j < res.data.length; j++ ){
                html += init_row( res.data[j] );
            }
            $(".main-tbody").html(html);
            $('table#main-table').DataTable({ "order": [] });
        });

        $(document).on("click", "[data-role='girisdetay']", function(){
           GitasREQ.parca_giris_detay( $(this).attr("data-id"), function(res){ Popup.on(res.data, "Form Detay"); } );
        });


    });
</script>