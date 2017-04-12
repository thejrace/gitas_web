<?php

/**
 * Created by PhpStorm.
 * User: Jeppe
 * Date: 12.04.2017
 * Time: 15:57
 */
class Parca{

    public static function get( $data ){
        $Parca = new Barkodlu_Parca( $data );
        if( $Parca->exists() ){
            return $Parca;
        } else {
            $Parca = new Barkodsuz_Parca( $data );
            if( $Parca->exists() ) return $Parca;
        }
        return null;
    }

}