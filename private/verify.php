<?php


/*function checkLogin(){
    if(isset($_SESSION['user_id'])){
        return true;
    }else{
        echo 'NotLogIn';
        exit();
    }
}*/


function checkPermission($isOther,$isAdmin){

    $permission = (!$isOther)||($isAdmin);
    /*$checkResult = [
        'permission'=>$permission,

    ];
    if($permission){
        $checkResult['message'] = 'permissionDeny';
    }else{
        $checkResult['message'] = 'permissionDeny';
    }*/
    return $permission;
}