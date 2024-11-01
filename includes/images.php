<?php
// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

if(isset($_GET['id']) && $_GET['id']) :
    $url = (base64_decode($_GET['id']));
    $w = intval($_GET['w']);
    $h = intval($_GET['h']);
    $x = intval($_GET['x']);
    $y = intval($_GET['y']);
    if(isset($url) && $url) :
        
        if(!class_exists('WideImage')) {
            require_once 'libs/wideimage/WideImage.php';
        }
        
        $image = WideImage::load($url);
        $cropped = $image->crop($x, $y, $w, $h);
        $cropped->output('jpg', 90);
    endif;
    exit;
endif;