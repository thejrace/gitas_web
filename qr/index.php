<?php    
/*
 * PHP QR Code encoder
 *
 * Exemplatory usage
 *
 * PHP QR Code is distributed under LGPL 3
 * Copyright (C) 2010 Dominik Dzienia <deltalab at poczta dot fm>
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 3 of the License, or any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
 */

    define("MAIN_DIR", $_SERVER["DOCUMENT_ROOT"] . "/gitasWeb/");
    echo "<h1>PHP QR Code</h1><hr/>";
    
    //set it to writable location, a place for temp generated PNG files
    $PNG_TEMP_DIR = MAIN_DIR . "res/qr/";
    
    //html PNG location prefix
    $PNG_WEB_DIR = 'temp/';

    include "qrlib.php";    
    
    //ofcourse we need rights to create temp dir
    if (!file_exists($PNG_TEMP_DIR))
        mkdir($PNG_TEMP_DIR);

    if(!file_exists($PNG_TEMP_DIR."/".$_GET["pgid"]."/")){
        mkdir($PNG_TEMP_DIR."/".$_GET["pgid"]."/" );
    }
    
    $filename = $PNG_TEMP_DIR."/".$_GET["pgid"]."/" .$_GET["data"].'.png';
    
    //processing form input
    //remember to sanitize user input in real-life solution !!!
    $errorCorrectionLevel = 'L';
    if (isset($_REQUEST['level']) && in_array($_REQUEST['level'], array('L','M','Q','H')))
        $errorCorrectionLevel = $_REQUEST['level'];    

    $matrixPointSize = 4;
    if (isset($_REQUEST['size']))
        $matrixPointSize = min(max((int)$_REQUEST['size'], 1), 10);


    if (isset($_REQUEST['data'])) { 
    
        //it's very important!
        if (trim($_REQUEST['data']) == '')
            die('data cannot be empty! <a href="?">back</a>');
            
        // user data
        //$filename = $PNG_TEMP_DIR.'test'.md5($_REQUEST['data'].'|'.$errorCorrectionLevel.'|'.$matrixPointSize).'.png';
        //$filename = $PNG_TEMP_DIR.$_GET["dosya_isim"].'.png';
        QRcode::png($_REQUEST['data'], $filename, $errorCorrectionLevel, $matrixPointSize, 2);
        
    } else {    
    
        //default data
        echo 'You can provide data in GET parameter: <a href="?data=like_that">like that</a><hr/>';    
        QRcode::png('PHP QR Code :)', $filename, $errorCorrectionLevel, $matrixPointSize, 2);    
        
    }    
        
    //display generated file
    echo '<img src="'.$PNG_WEB_DIR.basename($filename).'" /><hr/>';  
    
    //config form
    echo '<form action="index.php" method="post">
        Data:&nbsp;<input name="data" value="'.(isset($_REQUEST['data'])?htmlspecialchars($_REQUEST['data']):'PHP QR Code :)').'" />&nbsp;
        ECC:&nbsp;<select name="level">
            <option value="L"'.(($errorCorrectionLevel=='L')?' selected':'').'>L - smallest</option>
            <option value="M"'.(($errorCorrectionLevel=='M')?' selected':'').'>M</option>
            <option value="Q"'.(($errorCorrectionLevel=='Q')?' selected':'').'>Q</option>
            <option value="H"'.(($errorCorrectionLevel=='H')?' selected':'').'>H - best</option>
        </select>&nbsp;
        Size:&nbsp;<select name="size">';
        
    for($i=1;$i<=10;$i++)
        echo '<option value="'.$i.'"'.(($matrixPointSize==$i)?' selected':'').'>'.$i.'</option>';
        
    echo '</select>&nbsp;
        <input type="submit" value="GENERATE"></form><hr/>';
        
    // benchmark
    QRtools::timeBenchmark();


    $im_1 = imagecreatetruecolor(95, 145);
    $white = imagecolorallocate($im_1, 255, 255, 255);
    imagefilledrectangle($im_1, 0, 0, 95, 145, $white);

    imagecopymerge($im_1, imagecreatefrompng( $PNG_TEMP_DIR."/".$_GET["pgid"]."/" .$_GET["data"].'.png'), 0, 0, 0, 0, 90, 90, 100);

    // Create the image
    //$im = imagecreatetruecolor(99, 54);
    $im = imagecreatetruecolor(95, 145);

    // Create some colors
    $white = imagecolorallocate($im, 255, 255, 255);
    $black = imagecolorallocate($im, 0, 0, 0);
    imagefilledrectangle($im, 0, 0, 95, 145, $white);

    // The text to draw
    $text_1 = $_GET["parca_tipi"];
    $text_2 = $_GET["aciklama"];


    // Add the text
    imagettftext($im, 10, 0, 2, 12, $black, "montserrat.ttf", $text_1);
    imagettftext($im, 7, 0, 2, 25, $black, "montserrat.ttf", $text_2);

    $filename_yazi = $PNG_TEMP_DIR."/".$_GET["pgid"]."/" .$_GET["data"].'.png';

    imagecopymerge($im_1, $im, 0, 92, 0, 0, 95, 145, 100);
    imagepng(imagerotate($im_1, 90, 0), $filename_yazi);
    imagedestroy($im_1);

    