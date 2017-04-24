<?php
/**
 * Created by PhpStorm.
 * User: Jeppe
 * Date: 21.03.2017
 * Time: 17:00
 */
    require '../inc/init.php';


    if( $_POST ){

        $OK = 1;
        $TEXT = "";
        $DATA = array();

        switch( Input::get("req") ){






        }

        $output = json_encode(array(
            "ok"    => $OK,
            "text"  => $TEXT,
            "data"  => $DATA,
            "oh"    => $_POST
        ));
        echo $output;
        die;

    }