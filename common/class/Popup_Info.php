<?php

/**
 * Created by PhpStorm.
 * User: Jeppe
 * Date: 12.04.2017
 * Time: 01:41
 */
class Popup_Info{

    public static function init( $data ){
        $body = '<div class="detay-popup">';
        foreach( $data as $row ){
            $body .= '<div class="input-row">';
            foreach( $row as $item ){
                $body .= '<div class="input-col">'.new Template_Detay_Cont( $item["label"], $item["value"] ) . '</div>';
            }
            $body .= '</div>';
        }
        $body .= '</div>';
        return $body;
    }

}