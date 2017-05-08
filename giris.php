<?php

    $GIRIS_FLAG = true;
    require 'inc/init.php';

    if( Active_User::get_details("seviye") != "" && Active_User::get_details("eposta") != "" && Active_User::get_details("isim") != "" ) header("Location: index.php");

    if( $_POST ){

        $OK = 1;
        $TEXT = "";
        $DATA = array();
        $input_output = array();

        $INPUT_LIST = array(
            "pass"                      => array(array("req" => true), ""),
            "eposta"                    => array(array("req" => true, "email" => true ), "")
        );

        $Login = new Login();
        if( !$Login->action(array( "eposta" => Input::get("eposta"), "pass" => Input::get("pass"), "remember_me" => true ) ) ){
            $OK = 0;
        }
        $TEXT = $Login->get_return_text();

        $output = json_encode(array(
            "ok"        => $OK,
            "text"      => $TEXT,
            "data"      => $DATA,
            "inputret"  => $input_output, // form input errorlari
            "oh"        => $_POST
        ));
        echo $output;
        die;

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

    <title>Giriş Yap</title>


    <!-- <link rel="icon" type="image/png" href="/favicon-32x32.png" sizes="32x32">
    <link rel="icon" type="image/png" href="/android-chrome-192x192.png" sizes="192x192">
    <link rel="apple-touch-icon" sizes="180x180" h ref="/apple-touch-icon-180x180.png">-->

    <style>

        .giris-form{
            padding:10px;
            margin-top:20px;

        }

    </style>

</head>
<body>




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

        </div>
    </div>
    <div class="page-header">Giriş Yape</div>
    <div id="container">
        <div class="giris-form">
            <div class="form">
                <div class="form-notf"></div>
                <form action="" method="POST" id="giris_form">
                    <div class="input-row">
                        <div class="binput-container">
                            <label for="eposta">Eposta</label>
                            <input type="text" id="giris_form_eposta" name="eposta" class="req email" />
                        </div>
                    </div>
                    <div class="input-row">
                        <div class="binput-container">
                            <label for="pass">Şifre</label>
                            <input type="password" id="giris_form_pass" name="pass" class="req" />
                        </div>
                    </div>
                    <div class="input-row">
                        <button class="mnbtn mor">Giriş Yap</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">

    $(document).ready(function(){

        $("#giris_form").submit(function(ev){
            if( FormValidation.check(this)){
                GitasREQ.giris( $(this).serialize(), function(res){
                    if( res.ok ){
                        $(".giris-form").html("");
                        location.reload();
                    }
                });
            }
            ev.preventDefault();
        });

    });

</script>