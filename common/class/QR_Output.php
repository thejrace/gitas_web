<?php

/**
 * Created by PhpStorm.
 * User: Jeppe
 * Date: 23.04.2017
 * Time: 20:18
 */
class QR_Output{


    public static function olustur( $stok_kodu, $t1, $t2 ){
        $PNG_TEMP_DIR = DIR_QR_TEMP;
        $fname = $PNG_TEMP_DIR. $stok_kodu . ".png";
        // QR barkodu olustur
        QRcode::png($stok_kodu, $fname, "H", 2, 2);
        // dis cerceve ( qr ve yaziyi tutacak )
        $im_1 = imagecreatetruecolor(95, 145);
        $white = imagecolorallocate($im_1, 255, 255, 255);
        imagefilledrectangle($im_1, 0, 0, 95, 145, $white);
        imagecopymerge($im_1, imagecreatefrompng( $fname ), 0, 0, 0, 0, 90, 90, 100);
        // parça tipi ve aciklama resimleri
        $im = imagecreatetruecolor(95, 145);
        $white = imagecolorallocate($im, 255, 255, 255);
        $black = imagecolorallocate($im, 0, 0, 0);
        imagefilledrectangle($im, 0, 0, 95, 145, $white);
        $text_1 = $t1;
        $text_2 = $t2;
        // yazilari yaz
        imagettftext($im, 10, 0, 2, 12, $black, "qr/montserrat.ttf", $text_1);
        imagettftext($im, 7, 0, 2, 25, $black, "qr/montserrat.ttf", $text_2);
        // qr refi aliyoruz
        $filename_yazi = $PNG_TEMP_DIR. $stok_kodu.'.png';
        // birlestir hepsini
        imagecopymerge($im_1, $im, 0, 92, 0, 0, 95, 145, 100);
        // tanex sticker boyutlari icin 90 derece cevir son resmi
        imagepng(imagerotate($im_1, 90, 0), $filename_yazi);
        imagedestroy($im_1);
        return URL_QR_TEMP . $stok_kodu.'.png';
    }

}