<?php

/**
 * Created by PhpStorm.
 * User: Jeppe
 * Date: 09.04.2017
 * Time: 19:00
 */
class Template_Detay_Cont{
    private $title, $val;
    public function __construct( $title, $val ){
        $this->title = $title;
        $this->val = $val;
    }

    public function __toString(){
        return
            '<div class="input-container au">
                <label>'.$this->title.'</label>
                <span class="detay-div">'.$this->val.'</span>
            </div>';
    }

}