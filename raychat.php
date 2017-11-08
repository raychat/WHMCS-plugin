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


function raychat_config() {
    $configarray = array(
        "name" => "رایچت - raychat.io",
        "description" => "پلتفرم گفتگوی آنلاین و ارتباط با مشتریان رایچت",
        "version" => "1.0.2",
        "author" => "P30web",
        "language" =>"farsi",
        "fields" => array(
            "channeltoken" => array(
                "FriendlyName" => "توکن کانال",
                "Type" => "text",
                "Size" => "36",
                "Description" => "توکن کانال رایچت را وارد کنید",
                "Default" => ""
            ),
            "RaychatUnreg" => array (
                "FriendlyName" => "غیر فعال برای مهمان ها",
                "Type" =>  "yesno",
                "Size" => "55",
                "Description" => "اگر این گزینه را تیک بزنید ، پلاگین گفتگوی آنلاین برای کاربرانی که در سایت شما ثبت نام نکرده اند یا وارد نشده اند نمایش داده نمی شود . یا به صورت ساده تر پلاگین فقط برای کاربران عضو و کسانی که وارد شده اند نمایش داده میشود .",
                "Default" => "",
            ),
            "RaychatReg" => array (
                "FriendlyName" => "غیر فعال برای کاربران",
                "Type" =>  "yesno",
                "Size" => "55",
                "Description" => "اگر این گزینه را تیک بزنید ، پلاگین برای کاربرانی که ثبت نام کرده اند و یا کاربرانی که وارد سایت شده اند نمایش داده نمیشود ، یا به صورت ساده تر گفتگوی انلاین فقط برای مهمان ها و کاربرانی که ثبت نام کرده اند نمایش داده میشود . ",
                "Default" => "",
            ),
            "PageDis" => array(
                "FriendlyName" => "صفحات غیر فعال",
                "Type" => "text",
                "Size" => "36",
                "Description" => "<br>" ."صفحاتی که مایل هستید در آنها گفتگوی آنلاین نمایش داده نشود ، را در این بخش وارد کنید ، برای جداکردن چند آدرس از کاما (,) استفاده کنید ، برای کسب اطلاعات بیشتر میتوانید به بخش (مستندات) مراجعه نمایید." . "<br><b style='color: red'>*اگر میخواهید گفتگوی آنلاین در تمامی صفحات نمایش داده شود ، این بخش را خالی بگذارید.</b>",
                "Default" => ""
            ),
            "RaychatDebug" => array (
                "FriendlyName" => "حالت اشکال زدایی",
                "Type" =>  "yesno",
                "Size" => "55",
                "Description" => "ایا پلاگین روی سایت شما کار نمی کند ؟ ، برای این که بفهمید مشکل از کجاست ، ابتدا تیک این قسمت را بزنید ، سپس تنظیمات رو ذخیره کنید و به لینک (<b style='color:red;'><a href='addonmodules.php?module=raychat&debug=on' target='_blank'>صفحه مدیریت پلاگین</a></b>) بروید .",
                "Default" => "",
            ),
            "EnfromAdmin" => array (
                "FriendlyName" => "نمایش فقط برای مدیران",
                "Type" =>  "yesno",
                "Size" => "55",
                "Description" => "با فعال کردن این گزینه تمامی خروجی های های پلاگین فقط برای مدیران سایت نمایش داده میشود ، یعنی فقظ اگر به عنوان مدیر وارد سایت شده باشید خروجی های پلاگین نمایش داده میشود ، در غیر این صورت پلاگین هیچ خروجی نخواهد داشد .  <b style='color:red;'>این بخش فقط برای موارد تست پیشنهاد میشود.</b>",
                "Default" => "",
            ),
        )
    );
    return $configarray;
}

/**
 * Displays the Raychat configurable module output
 *
 * Access public
 * Return mixed
 */

function raychat_output($vars) {

    $moduleVersion= $vars['version'];
//    echo "<pre style='text-align:left;direction:ltr;'>";
//    print_r($GLOBALS);
//    echo "<pre>";
    $Text = " به صفحه ";
    if($_GET['debug'] == "on") {
        $Text .= "<b style='color:#292dff;'>اشکال یابی</b>";
    }else {
        $Text .= "<b style='color:#292dff;'>مدیریت</b>";
    }
    $Text .= " <a href='https://raychat.io/' target='_blank' class='autoLinked'>رایچت</a> :  ";
    $Text .= " خوش آمدید ! ";
    $Text .= "<br>";
    // erorr - warning - success
    $e = 0;
    $w = 0;
    $s = 0;
    $Token = $vars['channeltoken'];
    $TokenStayus = preg_match("/[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}/", $Token);
    //echo $_GET['debug'];
    if(!$_GET['debug'] == "on"){
        if(!empty($Token)){
            if($TokenStayus == "1"){
                $Text .= "<div class='successbox'><strong><span class='title'>رایچت شما با موفقیت فعال شده است </span></strong><br>برای فعال سازی ابزارک فقط کافیست یک بار دیگر سایت خود را بارگذاری کنید.</div>";
                $Text .= "<div class='gray_form'>";
                $Text .= "<p>1.ورود به پنل اپراتوری</p>";
                $Text .= "<p><a class='btn btn-primary' href='https://app.raychat.io' target='_blank'>ورود به ناحیه کاربری</a></p>";
                $Text .= "<p>2.شخصی سازی ابزارک یا مدیریت اپراتور ها از طریق پنل مدیریت</p>";
                $Text .= "<p>بعد از نصب و فعال سازی ابزارک برای هر چه بهتر مدیریت کردن اپراتور ها و شخصی سازی ابزارک میتوانید از طریق پنل مدیریت اقدام کنید</p>";
                $Text .= "<a class='btn btn-primary' href='https://raychat.io/login' target='_blank'>ورود به پنل مدیریت</a>";
                $Text .= "</div>";
            }elseif ($TokenStayus == "0"){
                $Text .= "<div class='infobox'><strong><span class='title'>متاسفانه کد توکن وارد شده اشتباه است و رایچت شما فعال نشده ! </span></strong>";
                $Text .= "<br> از طریق این لینک (<a href='configaddonmods.php'>صفحه افزوهه های whmcs</a>) به صفحه افزونه ها بروید و از قسمت تنظیمات رایچت کد توکن را اصلاح نمایید . <br> یا از تنطیمات بروید و حالت اشکال یابی را فعال نمایید ، تا به کمک این سیستم بتوانید مشکل را رفع کنید.</div>";
            }
        }elseif (empty($Token)){
            $Text .= "<div class='errorbox'><strong><span class='title'>رایچت فعال نمی باشد ، توکن را وارد کنید ! </span></strong>";
            $Text .= "<br> از طریق این لینک (<a href='configaddonmods.php'>صفحه افزوهه های whmcs</a>) به صفحه افزونه ها بروید و از قسمت تنظیمات رایچت کد توکن را وارد کنید . </div>";
            $Text .= "<div class='gray_form'>";
            $Text .= "<p> تبریک میگوییم، شما برای نصب ابزارک رایچت در سایتتان نصف راه را پیموده اید :)</p>";
            $Text .= "<p>اکنون از پنل : " . "<p><a class='btn btn-primary' href='http://raychat.io/admin' target='_blank'> مدیریت رایچت </a></p>" . "<span style='color: #292dff'>از قسمت تنظیمات کانال : </span>" . "<span style='color: red'>توکن کانال مورد نظر را در کادر زیر وارد کنید.</span>" . "</p>";
            $Text .= "<p>چنانچه تا کنون در رایچت عضو نشده اید میتوانید از طریق لینک زیر در رایچت عضو شوید و به صورت نامحدود " . " با کاربران وبسایتتون مکالمه کنید و فروش خود را چند برابر کنید " . "</p>";
            $Text .= "<p><a class='btn btn-primary' href='http://raychat.io/signup' target='_blank'>عضویت رایگان</a></p><p style='font-size: 12px'>رایچت، ابزار گفتگوی آنلاین |<a href='http://raychat.io/' target='_blank'>دمو</a>";
            $Text .= "</div>";
        }
    }
    if($_GET['debug'] == "on") {
        $Text .= "<br>";
        $Text .= " <b style='color:#0ab225;'>اگر رایچت شما به هر دلیلی باز نمیشه ، یا هر مشکلی داره ، ما در این صفحه به شما کمک میکنیم تا مشکل رو رفع کنیم .</b> ";
    }
    $Text .= "<style>p.gray{
    color: #808080;
}

div.gray_form {
    background-color: #ebebeb;
    padding: 20px;
    max-width: 100%;
    -moz-border-radius: 5px !important;
    -webkit-border-radius: 5px !important;
    -o-border-radius: 5px !important;
    border-radius: 5px !important;
}

div.gray_form p{color: #666666;}
td.gray div{
    max-width: 400px;
    color: #666666;
    float: left;
}

td.input {
    max-width: 27em;
}
p.small {
    font-size: 70%;
}
input.regular-text{
    width: 18em;
}
.form-table td {
    vertical-align: top;
}
.health-status-blocks {
    margin: 10px 0;
}

.health-status-col-margin {
    margin-right: -7.5px;
    margin-left: -7.5px;
}

.health-status-col-margin .col-sm-4 {
    padding-left: 7.5px;
    padding-right: 7.5px;
}

.health-status-block {
    margin: 5px 0;
    border-radius: 4px;
    color: #fff;
}

.health-status-block .icon {
    border-radius: 0 4px 4px 0 !important;
}
.health-status-block-success .icon {
    background-color: #3fa93f;
}

.health-status-block .icon {
    float: right;
    display: inline-block;
    width: 30%;
    height: 70px;
    font-size: 3em;
    line-height: 70px;
    text-align: center;
    border-radius: 4px 0 0 4px;
}

.health-status-block .detail {
    border-radius: 4px 0 0 4px;
}
.health-status-block-success .detail {
    background-color: #50c350;
}
.health-status-block .detail {
    float: right;
    display: inline-block;
    padding: 8px 15px;
    width: 70%;
    height: 70px;
}
.health-status-block .detail span.count {
    font-size: 2em;
    white-space: nowrap;
    overflow: hidden;
}
.health-status-block .detail span {
    display: block;
}

.health-status-block .detail span.desc {
    margin-top: -5px;
    font-size: .8em;
    white-space: nowrap;
    overflow: hidden;
}

.health-status-block-warning .icon {
    background-color: #e69d36;
}

.health-status-block-warning .detail {
    background-color: #f2b968;
}

.health-status-block-danger .icon {
    background-color: #ce3636;
}

.health-status-block-danger .detail {
    background-color: #ec4f4f;
}


</style>";

    $cheek_url = "http://rayanbartar.com/v.php";
    $cheek = file_get_contents($cheek_url);
    $cheek = trim($cheek);
    $SmoduleVersion = "";
    if($moduleVersion == $cheek){
        $s++;
        $SmoduleVersion = "خیلی خوبه ، شما از آخرین نسخه پلاگین استفاده می کنید .";
    }else {
        $e++;
        $SmoduleVersion = "شما از آخرین نسخه پلاگین استفاده نمی کنید . جهت دانلود آخرین ورژن بر روی لینک (<b style='color:#8e10ff;'><a href='http://rayanbartar.com/v.php?dl=1' target='_blank'>دانلود آخرین نسخه پلاگین</a></b>) کلیک کنید .";
    }
    $MemberStatus = $vars['RaychatReg'];
    $TextMemberStatus ="";
    if($MemberStatus == "on"){
        $w++;
        $TextMemberStatus = "<b style='color:#e69d36;'>" . "آیتم : غیر فعال برای کاربران در پنل تنظیمات فعال می باشد ، در نتیجه پلاگین گفتگوی آنلاین برای کاربران سایت نمایش ندارد ." . "</b>";
    }else{
        $s++;
        $TextMemberStatus = "<b style='color:#0ab225'>" . "آیتم : غیر فعال برای کاربران در پنل تنظیمات غیر فعال می باشد ، در نتیجه پلاگین گفتگوی آنلاین برای کاربران سایت نمایش دارد ." . "</b>";
    }
    $TextTokenStayus = "";
    $TokenText = "";
    if(!empty($Token)){
        $TokenText = "کد توکن وارد شده : " . $Token;
        $s++;
        if($TokenStayus == "1"){
            $s++;
            $TextTokenStayus = "<span style='color: #0ab225'>کد وارد شده معتبر می باشد .</span>";
        }elseif($TokenStayus == "0") {
            $e++;
            $TextTokenStayus = "<span style='color: #ff0000'>کد وارد شده اشتباه است</span>" . " , " .  " نمونه توکن کد : 7121170f-8eb1-4fb7-a118-91631421cb9c ";
        }
    }elseif(empty($Token)){
        $e++;
        $TokenText = "<span style='color: #ff0000'>توکن کد خالی می باشد ، توکن کد نباید خالی باشد ، به صفحه تنظیمات افزونه مراجعه کنید و بخش توکن کد را تکمیل کنید .</span>" ;
        $TextTokenStayus = "<span style='color: #ff0000'>کد توکن وارد نشده است .</span>";
    }

    $GastStatus = $vars['RaychatUnreg'];
    $TextGastStatus = "";
    if($GastStatus == "on"){
        $w++;
        $TextGastStatus = "<b style='color:#e69d36;'>" . "آیتم : غیر فعال برای مهمان ها در پنل تنظیمات فعال می باشد ، در نتیجه پلاگین گفتگوی آنلاین به مهمان ها (کسانی که ثبت نام نکردند) سایت نمایش داده نمیشود !  ." . "</b>";
    }else{
        $s++;
        $TextGastStatus = "<b style='color:#0ab225'>" . "آیتم : غیر فعال برای مهمان ها در پنل تنظیمات غیر فعال می باشد ، در نتیجه پلاگین گفتگوی آنلاین به مهمان ها ((کسانی که ثبت نام نکردند) در سایت نمایش داده میشود . ." . "</b>";
    }

    $UrlDisable = $vars['PageDis'];
    $TextUrlDisable = "";
    if(!empty($UrlDisable)){
        $w++;
        $TextUrlDisable = "<b style='color:#e69d36;'>" . " پلاگین گفتگوی آنلاین در صفحات : " . $UrlDisable . " غیر فعال می باشد . " ."</b>";
    }elseif(empty($UrlDisable)){
        $s++;
        $TextUrlDisable = "<b style='color:#0ab225;'>" . "حیلی خوبه ، پلاگین در تمامی صفحات نمایش داده میشود . " . "</b>";
    }

    $EnfromAdminValue = $vars['EnfromAdmin'];

    $TextEnfromAdmin = "";

    if($EnfromAdminValue == "on"){
        $w++;
        $TextEnfromAdmin = "<b style='color:#e69d36;'>" . " پلاگین گفتگوی آنلاین فقط برای مدیران سایت قابل نمایش می باشد و هیچ کدام از کاربران مهمان و عضو نمیتوانند آن را مشاهده کنند.  " .  " پیشنهاد میکنیم ، از بخش :   " . " <a href='configaddonmods.php' target='_blank'>تنظیمات افزونه ها</a> "  . "تیک گزینه نمایش فقط برای مدیران را بردارید."  ."</b>";
    }else{
        $s++;
        $TextEnfromAdmin = "<b style='color:#0ab225;'>" . "حیلی خوبه ، حالت مدیران غیر فعال می باشد . " . "</b>";
    }

$whmcsversion = $GLOBALS['CONFIG']['Version'];

    $WhmcsVersionText ="";
    if(!empty($whmcsversion)){
        $s++;
       $WhmcsVersionText = "شما از نسخه" . " <b style='direction: ltr;text-align: left;display: inline-block;color: #004bf3;'>$whmcsversion</b> " .  " دبلیو اچ ام سی اس استفاده می کنید .  ";
    }else{
        $w++;
        $WhmcsVersionText = "ورژن whmcs شما مشخص نیست .";
    }

    $phpVersion = phpversion();
    $textphpVersion = "";
    if(!empty($phpVersion)){
        $s++;
        $textphpVersion = "نسخه php سایت شما " . " <b style='direction: ltr;text-align: left;display: inline-block;color: #004bf3;'> $phpVersion </b> " . "می باشد ." ;
    }else{
        $textphpVersion = "نامشخص";
    }

    if($_GET['debug'] == "on"){
        $Text .= "<br><br>";
        if($vars['RaychatDebug'] == "on"){
            $s ++;
            $Text .= "تبریک ، حالت اشکال یابی پلاگین whmcs رایچت به خوبی فعال شده است !";
            $Text .= "<div class='health-status-blocks'><div class='row health-status-col-margin p30web_css'>";
            $Text .= "<div class='col-sm-4'><div class='health-status-block health-status-block-success clearfix'><div class='icon'><i class='fa fa-check'></i></div>";
            $Text .= "<div class='detail'><span class='count'>$s</span><span class='desc'>بررسی موفقیت آمیز بود.</span></div></div></div>";
            $Text .= "<div class='col-sm-4'><div class='health-status-block health-status-block-warning clearfix p30web_css'><div class='icon'><i class='fa fa-warning'></i></div>";
            $Text .= "<div class='detail'><span class='count'>$w</span><span class='desc'>هشدار ها</span></div></div></div>";
            $Text .= "<div class='col-sm-4'><div class='health-status-block health-status-block-danger clearfix'><div class='icon'><i class='fa fa-times'></i></div>";
            $Text .= "<div class='detail'><span class='count p30web_css'>$e</span><span class='desc'>به توجه نیاز دارند</span></div></div></div>";
            $Text .= "</div></div>";
            $Text .= "مشخصات رایچت شما : ";
            $Text .= "<br><br>";
            $Text .= "<table class='form' width='100%' cellspacing='2' cellpadding='3' border='0'><tbody>";
            $Text .= "<tr>";
            $Text .= "<td class='fieldlabel' width='30%'>ورژن whmcs شما : </td>";
            $Text .= "<td class='fieldarea'>$WhmcsVersionText</td>";
            $Text .= "</tr>";
            $Text .= "<tr>";
            $Text .= "<td class='fieldlabel' width='30%'>نسخه php سایت شما : </td>";
            $Text .= "<td class='fieldarea'><b>$textphpVersion</b></td>";
            $Text .= "</tr>";
            $Text .= "<tr>";
            $Text .= "<td class='fieldlabel' width='30%'>نسخه پلاگین نصب شده</td>";
            $Text .= "<td class='fieldarea'>$moduleVersion</td>";
            $Text .= "</tr>";
            $Text .= "<tr>";
            $Text .= "<td class='fieldlabel' width='30%'>آخرین نسخه فعلی : </td>";
            $Text .= "<td class='fieldarea'><b>$cheek</b></td>";
            $Text .= "</tr>";
            $Text .= "<tr>";
            $Text .= "<td class='fieldlabel' width='30%'>وضعیت پلاگین : </td>";
            $Text .= "<td class='fieldarea'><b>$SmoduleVersion</b></td>";
            $Text .= "</tr>";
            $Text .= "<tr>";
            $Text .= "<td class='fieldlabel' width='30%'>توکن کد کانال شما :</td>";
            $Text .= "<td class='fieldarea'><b>$TokenText</b></td>";
            $Text .= "</tr>";
            $Text .= "<tr>";
            $Text .= "<td class='fieldlabel' width='30%'>وضعیت تو کن کد : </td>";
            $Text .= "<td class='fieldarea'><b>$TextTokenStayus</b></td>";
            $Text .= "</tr>";
            $Text .= "<tr>";
            $Text .= "<td class='fieldlabel' width='30%'>وضعیت نمایش برای کاربران : </td>";
            $Text .= "<td class='fieldarea'><b>$TextMemberStatus</b></td>";
            $Text .= "</tr>";
            $Text .= "<tr>";
            $Text .= "<td class='fieldlabel' width='30%'>وضعیت نمایش برای مهمان ها : </td>";
            $Text .= "<td class='fieldarea'><b>$TextGastStatus</b></td>";
            $Text .= "</tr>";
            $Text .= "<tr>";
            $Text .= "<td class='fieldlabel' width='30%'>وضعیت نمایش در صفحات سایت :</td>";
            $Text .= "<td class='fieldarea'><b>$TextUrlDisable</b></td>";
            $Text .= "</tr>";
            $Text .= "<tr>";
            $Text .= "<td class='fieldlabel' width='30%'>وضعیت نمایش فقط برای مدیران سایت : </td>";
            $Text .= "<td class='fieldarea'><b>$TextEnfromAdmin</b></td>";
            $Text .= "</tr>";
            $Text .= "</tbody></table>";
        }else {
            $Text .= "<b style='color: red'>هی وای من : حالت اشکال یابی پلاگین whmcs رایچت به خوبی فعال نشده است</b>";
            $Text .= "<br><br>";
            $Text .= "<b style='color: red'>برای فعال سازی ابتدا به لینک (<a href='configaddonmods.php' target='_blank'>فعال کردن حالت اشکال یابی</a>) بروید</b>";
            $Text .= "<br><br>";
            $Text .= "<b style='color: red'>ودر بخش تنظیمات پلاگین رایچت تیک گزینه (حالت اشکال زدایی) را بزنید.</b>";
            $Text .= "<br>";
        }
    }

    $Text .= "<br>";
    $Text .= "این نسخه توسط <a href='https://www.p30web.org/' target='_blank' class='autoLinked'>پی سی وب</a> برای whmcs برنامه نویسی شده است و با همکاری <a href='https://raychat.io/' target='_blank' class='autoLinked'>رایچت</a> به صورت عمومی و رایگان منتشر گردیده است .";
    echo $Text;


}