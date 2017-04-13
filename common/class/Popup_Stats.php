<?php

/**
 * Created by PhpStorm.
 * User: Jeppe
 * Date: 12.04.2017
 * Time: 14:02
 */
class Popup_Stats{
    public static $VERI_YOK = "Veri Yok";
    public static $OFF_POPUP = "offpopup";
    public static function init( $data, $tip = "" ){
        $body = "";
        foreach( $data as $section ){
            $item_body = "";
            foreach( $section["items"] as $item ){
                $href = "#";
                if( isset($item["href"]) && $item["val"] != self::$VERI_YOK ) $href = $item["href"];
                $item_body .=   '<li>'
                    .   '<a href="'.$href.'">'
                    .       '<div class="sol">'.$item["key"].'</div>'
                    .       '<div class="sag">'.$item["val"].'</div>'
                    .   '</a>'
                    . '</li>';
            }
            $body .=    '<div class="stat-section">'
                .        '<span>'.$section["header"].'</span>'
                .        '<ul>'
                .          $item_body
                .       '</ul>'
                .    '</div>';
        }
        return
         '<div class="popup-stat '.$tip.'">'
        .    $body
        .'</div>';
    }

}