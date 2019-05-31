<?php

function fver($filename){
    $filepath = $_SERVER['DOCUMENT_ROOT'].$filename;
    if (file_exists($filepath)) {
        return filemtime($filepath);
    }
    return '';
}
function prvar($var){
    echo '<pre>';
    var_dump($var);
    echo '</pre>';
    exit();
}
function print_dump($var){
    echo '<pre>';
    var_dump($var);
    echo '</pre>';
}

function thmb($photo,$size){
    return CFile::ResizeImageGet($photo, ['width'=>$size[0],'height'=>$size[1]], BX_RESIZE_IMAGE_EXACT, true)['src'];
}
function resize($photo,$size){
    return CFile::ResizeImageGet($photo, ['width'=>$size[0],'height'=>$size[1]], BX_RESIZE_IMAGE_PROPORTIONAL, true)['src'];
}