<?php
function jumpPage($jumpUrl,$head,$body){
    $html='<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="refresh" content="3;url='.$jumpUrl.'>
        '.$head.'
    </head>
    <body>'.$body.'</body>
    </html>
    ';
    return $html;
}
?>