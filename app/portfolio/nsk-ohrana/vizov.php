<?php
### Referals script start
error_reporting(-1);
ini_set('display_errors',1);
ini_set('log_errors',1);
ini_set('errors_log','error.log');
$debug=0;
$referers=array(
 array('name'=>'Yandex Direct','substr'=>'yandex.ru/yandsearch'),
 array('name'=>'Yandex Search','substr'=>'from=yandex.ru%3byandsearch%'),
 array('name'=>'Rambler','substr'=>'rambler.ru/search'),
 array('name'=>'Facebook','substr'=>'facebook.com/'),
 );
$googlereferers=array(
 array('name'=>'Google Search','substr'=>'/^https?\:\/\/(www\.)?google\.\w*\/\?/'),
 );
if ($debug)
 $lf=@fopen(dirname(__FILE__).'/'.'debug.log','a');
function writelog($s) {
 global $lf,$debug;
 if ($debug)
 fputs($lf,"$s\n");
}
$t=date('Y-m-d H:i:s');
$ip=$_SERVER['REMOTE_ADDR'];
$utmdata='';
$referer=isset($_SERVER['HTTP_REFERER']) ? strtolower(trim($_SERVER['HTTP_REFERER'])) : '';
$utm=isset($_GET['utm_source']) ? strtolower(trim($_GET['utm_source'])) : '';
$term=isset($_GET['utm_term']) ? strtolower(trim($_GET['utm_term'])) : '';
writelog("Session started (price.php) at '$t', IP='$ip', Referer='$referer', utm_source='$utm', utm_term='$term'");
writelog("Cokies: ".print_r($_COOKIE,true));
writelog("REQUEST: ".print_r($_REQUEST,true));
if (isset($_COOKIE['utmdata']))
 {
 writelog("Found saved cookie UTMdata");
 $utmdataexp=explode('&',$_COOKIE['utmdata']);
 if (count($utmdataexp)==3 && !empty($utmdataexp[0]) && !empty($utmdataexp[1]))
 {
 $t=$utmdataexp[0];
 $utm=$utmdataexp[1];
 $term=$utmdataexp[2];
 $utmdata=$t.'&'.$utm.'&'.$term;
 }
 }
$newutm=0;
//fputs($lf,"Processing UTMdata ($utm,$term)...\n");
if (!empty($referer))
 {
 foreach ($referers as $ref)
 {
 //writelog("Checking ".$ref['substr']." in $referer...");
 if (strpos($referer,$ref['substr'])!==false)
 {
 $utm=$ref['name'];
 
 writelog("Found substr for $utm...");
 $newutm=1;
 break;
 }
 }
 foreach ($googlereferers as $ref)
 {
 //writelog("Checking ".$ref['substr']." in $referer...");
 if (preg_match($ref['substr'],$referer))
 {
 $utm=$ref['name'];
 writelog("Found substr for $utm...");
 $newutm=1;
 break;
 }
 }
 }
if ($newutm)
 {
 $term='';
 if (!empty($referer))
 {
 if ($utm=='Yandex Direct' || $utm=='Yandex Search')
 {
 writelog("Processing term for $utm..."); 
 $s=preg_replace('/^.*\?(.*)$/','$1',$referer);
 $a=explode('&',$s);
 foreach ($a as $aa)
 {
 $ab=explode('=',$aa);
 if ($ab[0]=='text')
 $term=rawurldecode($ab[1]);
 }
 }
 if ($utm=='Rambler')
 {
 writelog("Processing term for $utm..."); 
 $s=preg_replace('/^.*\?(.*)$/','$1',$referer);
 $a=explode('&',$s);
 foreach ($a as $aa)
 {
 $ab=explode('=',$aa);
 if ($ab[0]=='query')
 $term=rawurldecode($ab[1]);
 }
 }
 }
 }
if ($newutm) 
 {
 writelog("Newutm: writing new UTM data...");
 $utmdata=$t.'&'.$utm.'&'.$term;
 setcookie('utmdata',$utmdata,time()+60*60*24*30);
 }
 writelog("UTM Data: $utmdata");
 
#### Referelas Script End
?>
<?php
function ValidateEmail($email)
{
   $pattern = '/^([0-9a-z]([-.\w]*[0-9a-z])*@(([0-9a-z])+([-\w]*[0-9a-z])*\.)+[a-z]{2,6})$/i';
   return preg_match($pattern, $email);
}
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['formid']) && $_POST['formid'] == 'form1')
{
   $mailto = 'zakaz@nsk-ohrana.ru';
   $mailfrom = isset($_POST['email']) ? $_POST['email'] : $mailto;
   $subject = 'Vizov nsk-ohrana.ru';
   $message = '';
   $success_url = './ok-vizov.php';
   $error_url = '';
   $error = '';
   $eol = "\n";
   $boundary = md5(uniqid(time()));
   $header  = 'From: '.$mailfrom.$eol;
   $header .= 'Reply-To: '.$mailfrom.$eol;
   $header .= 'MIME-Version: 1.0'.$eol;
   $header .= 'Content-Type: multipart/mixed; boundary="'.$boundary.'"'.$eol;
   $header .= 'X-Mailer: PHP v'.phpversion().$eol;
   if (!ValidateEmail($mailfrom))
   {
      $error .= "The specified email address is invalid!\n<br>";
   }
   if (!empty($error))
   {
      $errorcode = file_get_contents($error_url);
      $replace = "##error##";
      $errorcode = str_replace($replace, $error, $errorcode);
      echo $errorcode;
      exit;
   }
   $internalfields = array ("submit", "reset", "send", "filesize", "formid", "captcha_code", "recaptcha_challenge_field", "recaptcha_response_field", "g-recaptcha-response");
   $message .= $eol;
   $message .= "IP Address : ";
   $message .= $_SERVER['REMOTE_ADDR'];
   $message .= $eol;
   $logdata = '';
   foreach ($_POST as $key => $value)
   {
      if (!in_array(strtolower($key), $internalfields))
      {
         if (!is_array($value))
         {
            $message .= ucwords(str_replace("_", " ", $key)) . " : " . $value . $eol;
         }
         else
         {
            $message .= ucwords(str_replace("_", " ", $key)) . " : " . implode(",", $value) . $eol;
         }
      }
   }
   $body  = 'This is a multi-part message in MIME format.'.$eol.$eol;
   $body .= '--'.$boundary.$eol;
   $body .= 'Content-Type: text/plain; charset=UTF-8'.$eol;
   $body .= 'Content-Transfer-Encoding: 8bit'.$eol;
   $body .= $eol.stripslashes($message).$eol;
   if (!empty($_FILES))
   {
       foreach ($_FILES as $key => $value)
       {
          if ($_FILES[$key]['error'] == 0)
          {
             $body .= '--'.$boundary.$eol;
             $body .= 'Content-Type: '.$_FILES[$key]['type'].'; name='.$_FILES[$key]['name'].$eol;
             $body .= 'Content-Transfer-Encoding: base64'.$eol;
             $body .= 'Content-Disposition: attachment; filename='.$_FILES[$key]['name'].$eol;
             $body .= $eol.chunk_split(base64_encode(file_get_contents($_FILES[$key]['tmp_name']))).$eol;
          }
      }
   }
   $body .= '--'.$boundary.'--'.$eol;
   if ($mailto != '')
   {
      mail($mailto, $subject, $body, $header);
   }
   header('Location: '.$success_url);
   exit;
}
?>
<!doctype html>
<html lang="ru">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=8">
<meta charset="utf-8">
<title>Заказать звонок</title>
<meta name="robots" content="noindex, nofollow">
<meta name="revisit-after" content="365 days">
<meta http-equiv="imagetoolbar" content="no">
<script src="js/jquery-1.12.4.min.js"></script>
<script src="js/wb.validation.min.js"></script>
<script src="js/jquery-ui.min.js"></script>
<script src="fancybox/jquery.easing-1.3.pack.js"></script>
<script src="fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<script src="fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
<script src="js/wwb12.min.js"></script>
<script>   
   function displaylightbox(url, options)
   {
      options.padding = 0;
      options.autoScale = true;
      options.href = url;
      options.type = 'iframe';
      $.fancybox(options);
   }
</script>
<script>   
   $(document).ready(function()
   {
      $("#Form1").submit(function(event)
      {
         var isValid = $.validate.form(this);
         return isValid;
      });
      $("#Editbox1").validate(
      {
         required: true,
         type: 'number',
         expr_min: '>=',
         expr_max: '<=',
         value_min: '',
         value_max: '',
         length_min: '11',
         length_max: '11',
         color_text: '#000000',
         color_hint: '#00FF00',
         color_error: '#FF0000',
         color_border: '#808080',
         nohint: true,
         font_family: 'Arial',
         font_size: '12px',
         position: 'topleft',
         offsetx: 0,
         offsety: -35,
         effect: 'none',
         error_text: 'Проверьте номер: мобильный 89133456789 или городской 83831234567'
      });
      $("#indexjQueryButton6").button();
   });
</script>
<!--Google analytics-->
 <script>   
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
    
    ga('create', 'UA-64829478-1', 'auto'); //ТУТ МЕНЯТЬ НОМЕР НА СВОЙ
    ga('require', 'displayfeatures');
    ga('send', 'pageview');
   
    /* Accurate bounce rate by time */
    if (!document.referrer ||
    document.referrer.split('/')[2].indexOf(location.hostname) != 0)
    setTimeout(function(){
    ga('send', 'event', 'Новый посетитель', location.pathname);
    }, 15000);
</script>
<!--Google analytics-->


</head>
<body>
   <div id="space"><br></div>
   <div id="container">
      <div id="wb_Form1">
         <form name="Form1" method="post" action="<?php echo basename(__FILE__); ?>" enctype="multipart/form-data" accept-charset="UTF-8" target="_self" id="Form1">
            <input type="hidden" name="formid" value="form1">
            <input type="hidden" name="Время отправки заявки" value="<?=$t?>">
            <input type="hidden" name="Ключевик" value="<?=$utmdata?>">
            <input type="hidden" name="Источник" value="<?=$referer?>">
            <div id="wb_Text2">
               <span id="wb_uid0"><strong>БЕСПЛАТНЫЙ ВЫЗОВ КОНСУЛЬТАНТА</strong></span></div>
            <input type="text" id="Editbox4" name="Имя" value="" autocomplete="off" spellcheck="false" required="" placeholder=" &#1042;&#1074;&#1077;&#1076;&#1080;&#1090;&#1077; &#1080;&#1084;&#1103;">
            <div id="wb_Text3">
               <span id="wb_uid1">Оставьте заявку, мы свяжемся с вами в ближайшее время</span></div>
            <input type="text" id="Editbox1" name="тел" value="" autocomplete="off" spellcheck="false" placeholder=" &#1042;&#1074;&#1077;&#1076;&#1080;&#1090;&#1077; &#1090;&#1077;&#1083;">
            <div id="wb_callbackText1">
               <span id="wb_uid2">Имя:</span></div>
            <div id="wb_callbackText2">
               <span id="wb_uid3">Тел.:</span></div>
            <div id="wb_indexImage32">
               <img src="images/eco_a1.png" id="indexImage32" alt=""></div>
            <div id="wb_indexImage31">
               <img src="images/eco_a.png" id="indexImage31" alt=""></div>
            <div id="wb_indexText37">
               <span id="wb_uid4"><strong>ЗАКАЗАТЬ ЗВОНОК</strong></span></div>
            <button type="submit" id="indexjQueryButton6" onmouseover="ShowObject('wb_indexImage31', 0);return false;" onmouseout="ShowObject('wb_indexImage31', 1);return false;" name="" value="Получить расчет">Получить расчет</button>
         </form>
      </div>
   </div>
<!-- Yandex.Metrika counter -->
   <script>       (function (d, w, c) {
           (w[c] = w[c] || []).push(function() {
               try {
                   w.yaCounter35205560 = new Ya.Metrika({
                       id:35205560,
                       clickmap:true,
                       trackLinks:true,
                       accurateTrackBounce:true,
                       webvisor:true
                   });
               } catch(e) { }
           });
           var n = d.getElementsByTagName("script")[0],
               s = d.createElement("script"),
               f = function () { n.parentNode.insertBefore(s, n); };
           s.type = "text/javascript";
           s.async = true;
           s.src = "https://mc.yandex.ru/metrika/watch.js";
           if (w.opera == "[object Opera]") {
               d.addEventListener("DOMContentLoaded", f, false);
           } else { f(); }
       })(document, window, "yandex_metrika_callbacks");
   </script>
   <noscript><div><img src="https://mc.yandex.ru/watch/35205560" id="wb_uid5" alt=""/></div></noscript>

<!-- /Yandex.Metrika counter -->
<!-- Rating@Mail.ru counter -->
   <script>   
   var _tmr = window._tmr || (window._tmr = []);
   _tmr.push({id: "2768139", type: "pageView", start: (new Date()).getTime()});
   (function (d, w, id) {
     if (d.getElementById(id)) return;
     var ts = d.createElement("script"); ts.type = "text/javascript"; ts.async = true; ts.id = id;
     ts.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//top-fwz1.mail.ru/js/code.js";
     var f = function () {var s = d.getElementsByTagName("script")[0]; s.parentNode.insertBefore(ts, s);};
     if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); }
   })(document, window, "topmailru-code");
   </script><noscript><div id="wb_uid6">
         <img src="//top-fwz1.mail.ru/counter?id=2768139;js=na" height="1" width="1" alt="Рейтинг@Mail.ru"/>
      </div></noscript>
<!-- //Rating@Mail.ru counter --><link href="css/Copy of sunny/jquery-ui.min.css" rel="stylesheet">
<link href="css/wb.validation.css" rel="stylesheet">
<link href="http://fonts.googleapis.com/css?family=Roboto:400,900italic,900,700italic,700,500italic,500,400italic,300italic,300,100italic,100&subset=latin,cyrillic-ext,cyrillic" rel="stylesheet">
<link href="http://fonts.googleapis.com/css?family=PT+Sans:400,700italic,700,400italic&subset=latin,cyrillic-ext,cyrillic" rel="stylesheet">
<link href="css/vizov.css" rel="stylesheet">
<link rel="stylesheet" href="fancybox/jquery.fancybox-1.3.4.css">
<link rel="shortcut icon" href="favicon.ico">
<link rel="icon" type="image/gif" href="animated_favicon1.gif">
</body>
</html>