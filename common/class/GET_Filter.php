<?php

/**
 * Created by PhpStorm.
 * User: Jeppe
 * Date: 11.04.2017
 * Time: 21:50
 */
class GET_Filter {
    public static function sql( $string ){
        $output = array( "vals" => array(), "params" => array());
        $exp = explode("&", $string);
        $amp_temizle = false;
        if( count($exp) > 1 ) $amp_temizle = true;
        $c = 0;
        foreach( $exp as $pair ){
            $exp2 = explode("=", $pair);
            if( $amp_temizle && $c > 0 ){
                $output["params"][] = substr( $exp2[0], 4 ) ."=?";
            } else {
                $output["params"][] = $exp2[0] ."=?";
            }
            $output["vals"][] = $exp2[1];
            $c++;
        }
        $output["params"] = implode( " && ", $output["params"] );
        return $output;
    }
    public static function jsout( $get ){
        if( count($get) == 0 ) return "";
        $FILTER_ARRAY = array();
        foreach( $get as $key => $val ){
            if( substr($key, 0, 7)  == "filter_" ){
                $FILTER_ARRAY[] = substr($key, 7)."=".$val;
            }
        }
        if( count($FILTER_ARRAY) ) return "";
        return implode("&", $FILTER_ARRAY);
    }
}