<?php
/**
 * package : WHMCS Plugin
 * Name : Raychat
 * author : p30web Programming Team
 * author Url : p30web.org
 * Copyright  2008-2017 Alireza Ahmadi
 * First Version Date & Time : 1396/05/28 - Time : 04:04 PM
 * Last Update Date & Time : 1396/06/14 - Time : 04:36 AM
 * VerSion: 1.0.2
 * Property rights : All Rights Reserved by raychat.io.
 * Access public
 **/

if (!defined("WHMCS")) { die("This file cannot be accessed directly");}

use Illuminate\Database\Capsule\Manager as Capsule;

function hook_raychat_footer_output($vars)
{
    $uid = $_SESSION['uid'];

    $ChannelToken = Capsule::table('tbladdonmodules')->select('value')->WHERE('module', '=', 'raychat')->WHERE('setting', '=', 'channeltoken')->pluck('value');

    function GetValue($value){
        if(!empty($value)){
            if(is_array($value)){
                return $value[0];
            }elseif(!is_array($value)){
                return $value;
            }
        }elseif(empty($value)){
            return false;
        }
    }


    $ChannelTokenValue = GetValue($ChannelToken);


    //clients only : Show from by clients
    $clientsonly =  Capsule::table('tbladdonmodules')->select('value')->WHERE('module', '=', 'raychat')->WHERE('setting', '=', 'RaychatUnreg')->pluck('value');

    $clientsonlyValue = GetValue($clientsonly);

    if ($clientsonlyValue == "on") {
        if (empty($uid)) {
            return false;
        }
    }

    //maybe we just wanna chat with guests?
    $guestonly =  Capsule::table('tbladdonmodules')->select('value')->WHERE('module', '=', 'raychat')->WHERE('setting', '=', 'RaychatReg')->pluck('value');

    $guestonlyValue = GetValue($guestonly);

    if ($guestonlyValue == "on") {
        if (!empty($uid)) {
            return false;
        }
    }

    $output = "<!-- BEGIN RAYCHAT CODE --><script type=\"text/javascript\">!function(){function t(){var t=document.createElement(\"script\");t.type=\"text/javascript\",t.async=!0,localStorage.getItem(\"rayToken\")?t.src=\"https://app.raychat.io/scripts/js/\"+o+\"?rid=\"+localStorage.getItem(\"rayToken\")+\"&href=\"+window.location.href:t.src=\"https://app.raychat.io/scripts/js/\"+o;var e=document.getElementsByTagName(\"script\")[0];e.parentNode.insertBefore(t,e)}var e=document,a=window,o=\"$ChannelTokenValue\";\"complete\"==e.readyState?t():a.attachEvent?a.attachEvent(\"onload\",t):a.addEventListener(\"load\",t,!1)}();</script><!-- END RAYCHAT CODE -->";

    // currentpagelinkback : cplb
    $cplb = $vars['currentpagelinkback'];

    function GetUrl($string){
        $string = preg_replace("/\/(.*)\//i", "", $string);
        $search = array("/","=","&","amp;");
        $string = str_ireplace($search , "", $string);
        $string = trim($string);
        $string = preg_replace("/(.php)(\?)?([A-Za-z0-9=&]{1,})?/i", "", $string);
        return $string;
    }

    $cplb = GetUrl($cplb);
    
    //This section is for passive pages.
    $PageDis =  Capsule::table('tbladdonmodules')->select('value')->WHERE('module', '=', 'raychat')->WHERE('setting', '=', 'PageDis')->pluck('value');

    $PageDis = GetValue($PageDis);


    if(!empty($PageDis)){
        $PageDis = trim($PageDis);
        $PageDis = str_ireplace(" " , "" , $PageDis);
        // Single and multi page Cheek
        $smp = preg_match("/(,)/i", $PageDis);
        if($smp == "0"){
            if($cplb == $PageDis){
                return false;
            }
        }elseif ($smp == "1"){
            $AarrayPages = explode("," , $PageDis);
            if(in_array($cplb , $AarrayPages)){
                return false;
            }
        }
    }

    $EnFromAdmin =  Capsule::table('tbladdonmodules')->select('value')->WHERE('module', '=', 'raychat')->WHERE('setting', '=', 'EnfromAdmin')->pluck('value');

    $EnFromAdminValue = GetValue($EnFromAdmin);

    if($EnFromAdminValue == "on"){
        if(isset($_SESSION['adminid'])){
            return $output;
        }
    }else {
        return $output;
    }

}

add_hook('ClientAreaFooterOutput', 1, 'hook_raychat_footer_output');
