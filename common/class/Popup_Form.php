<?php

/**
 * Created by PhpStorm.
 * User: Jeppe
 * Date: 12.04.2017
 * Time: 01:40
 *
 */

class Popup_Form{

    public static   $POST = "POST",
                    $GET = "GET",
                    $SELECT = 1,
                    $TEXT = 2,
                    $TEXTAREA = 3,
                    $EMAIL = 4,
                    $PASSWORD = 5,
                    $HIDDEN = 6,
                    $CLS_REQ = "req",
                    $CLS_EMAIL = "email",
                    $CLS_POSNUM = "posnum",
                    $CLS_SELECT_NO_ZERO = "select_no_zero",
                    $CLS_KISA = "kisa",
                    $CLS_ORTA = "orta",
                    $CLS_UZUN = "uzun";

    public static function init( $data ){

        $formclass_html = "";
        if( isset($data["class"] ) ){
            if( is_array( $data["class"] ) ){
                foreach( $data["class"] as $formclass ){
                    $formclass_html .= $formclass . " ";
                }
            } else {
                $formclass_html = $data["class"];
            }
        }
        $body = "";
        foreach( $data["rows"] as $row ){
            $body .= '<div class="input-row">';
                foreach( $row as $col ){

                    if( $col["type"] != self::$HIDDEN ) $body .= '<div class="input-col"><div class="input-container au">';
                    $input_class = "";
                    if( isset($col["class"] ) ){
                        if( is_array($col["class"] ) ){
                            foreach( $col["class"] as $cls ) $input_class .= $cls . " ";
                        } else {
                            $input_class = $col["class"];
                        }
                    }
                    $input_val = "";
                    if( isset($col["value"] ) ) {
                        if( $col["type"] != self::$TEXTAREA ) {
                            $input_val = 'value="' . $col["value"] . '"';
                        } else {
                            $input_val = $col["value"];
                        }
                    }
                    $label = "";
                    if( isset($col["key"] ) ){
                        $label = '<label for="'.$data["id"].'_'.$col["name"].'">'.$col["key"].'</label>';
                    }
                    if( $col["type"] == self::$TEXT ){
                        $body .= $label.
                                 '<input type="text" name="'.$col["name"].'" id="'.$data["id"].'_'.$col["name"].'" ' . $input_val . ' class="'.$input_class. '"/>';
                    } else if( $col["type"] == self::$EMAIL ){
                        $body .= $label.
                            '<input type="email" name="'.$col["name"].'" id="'.$data["id"].'_"'.$col["name"].'" ' . $input_val . ' class="'.$input_class. '"/>';
                    } else if( $col["type"] == self::$TEXTAREA ){
                        $body .= $label . '<textarea name="'.$col["name"].'" id="'.$data["id"].'_'.$col["name"].'"  class="'.$input_class.'">'.$input_val.'</textare>';
                    } else if( $col["type"] == self::$SELECT ){
                        // selectlerin htmli hazir aliyorum ( Common::sqldb_select_html )
                        $body .= $label . $col["data"];
                    } else if( $col["type"] == self::$HIDDEN ){
                        $body .= '<input type="hidden" name="'.$col["name"]. '" value="'.$col["value"].'" />';
                    }
                    if( $col["type"] != self::$HIDDEN ) $body .= '</div></div>';
                }
            $body .= '</div>';
        }


        return
        '<div class="popup-form">'
        .   '<div class="form">'
        .       '<div class="form-notf"></div>'
        .       '<form action="'.$data["action"].'" method="'.$data["method"].'" id="'.$data["id"].'" class="'.$formclass_html.'" >'
        .           $body
        .           '<div class="input-row"><button class="mnbtn mor">KAYDET</button></div>'
        .       '</form>'
        .   '</div>'
        .'</div>';

    }

}