<?php 
//CREDIT | BOSSNZ
include 'uidfreefire.class.php';
//key antirecaptcha ไปหาซื้อกันเองนะครัชชช
$token_antirecaptcha = "";
$topup = New topup_freefire($token_antirecaptcha);
$uid = '950516437';
//login ด้วย uid freefire ก่อน
print_r($topup->login_freefire($uid));
//login เสร็จแล้วจะได้open_idไว้เติมฟรีฟายด้วยบัตรการีน่า
$open_id = '15e3bee77f5460d82b6000ed9f470d99';
$garenacard = '9811329697982418';
print_r($topup->topup_garenacard($open_id,$garenacard));
