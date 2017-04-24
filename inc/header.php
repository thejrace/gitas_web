<?php

    if( isset($AKTIVITE_KOD ) ){
        if( !in_array($AKTIVITE_KOD, $KULLANICI_IZINLER ) ) header("Location: index.php");
    }


?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge"> <!-- IE render en son versiyona gore -->
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <link rel="stylesheet" href="<?php echo URL_RES_CSS ?>main.css" />
        <link rel="stylesheet" href="<?php echo URL_RES_FONTS ?>fonts.css" />
		<link rel="stylesheet" href="<?php echo URL_RES_CSS ?>datatables.css" />
		<link rel="stylesheet" href="<?php echo URL_RES_CSS ?>jquery.datetimepicker.css" />

        <script type="text/javascript" src="<?php echo URL_RES_JS ?>common.js"></script>

        <script type="text/javascript" src="<?php echo URL_RES_JS ?>jquery.js"></script>
        <script type="text/javascript" src="<?php echo URL_RES_JS ?>jquery-ui.js"></script>
        <script type="text/javascript" src="<?php echo URL_RES_JS ?>main.js"></script>
		<script type="text/javascript" src="<?php echo URL_RES_JS ?>datatables.js"></script>
		<script type="text/javascript" src="<?php echo URL_RES_JS ?>jquery.datetimepicker.min.js"></script>
		<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:400,600,300,800,700,400italic|PT+Serif:400,400italic" />

        <title>BUS v2</title>


        <!-- <link rel="icon" type="image/png" href="/favicon-32x32.png" sizes="32x32">
        <link rel="icon" type="image/png" href="/android-chrome-192x192.png" sizes="192x192">
        <link rel="apple-touch-icon" sizes="180x180" h ref="/apple-touch-icon-180x180.png">-->

	</head>
	<body>


        <script type="text/javascript">

            GitasDT_CSS.ICO_SETS = <?php echo GitasDT_CSS::js_out( Active_User::get_details("seviye") ) ?>;

        </script>

		<div id="popup-overlay"></div>
    	<div id="popup" ></div>
        <div id="loader">
            <div class="rolling"><img src="<?php echo URL_RES_IMG ?>rolling.gif" /></div>
            <div class="sok">
                <span>Lütfen bekleyin...</span>
            </div>


        </div>

    	<div id="wrapper">
            <div class="header">
                <div id="container" class="clearfix">
                    <div class="test-header-nav" style="text-align:center; padding-top:20px">
                        <a href="<?php echo URL_ISEMRI_FORMLARI ?>" class="mnbtn gri">İŞ EMRİ FORMLARI</a>
                        <a href="<?php echo URL_ISEMRI_FORMU ?>" class="mnbtn gri">İŞ EMRİ FORMU YAZ</a>
                        <a href="<?php echo URL_OTOBUSLER ?>" class="mnbtn gri">OTOBÜSLER</a>
                        <a href="<?php echo URL_STOK ?>" class="mnbtn gri">STOK</a>
                        <a href="<?php echo URL_REVIZYON_TALEPLERI ?>" class="mnbtn gri">REVİZYON TALEPLERİ</a>
                        <a href="<?php echo URL_PARCA_GIRISLERI ?>" class="mnbtn gri">PARÇA GİRİŞLERİ</a>
                        <a href="<?php echo URL_PARCA_GIRISI ?>" class="mnbtn gri">PARÇA GİRİŞİ</a>
                    </div>
                </div>
            </div>

        <div class="page-header"><?php echo $TITLE ?></div>
        <div id="container">
            
          
  
