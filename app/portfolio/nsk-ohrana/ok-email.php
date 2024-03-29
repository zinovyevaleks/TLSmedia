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
<!doctype html>
<html lang="ru">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=8">
<meta charset="utf-8">
<title>Заявка принята, Спасибо!</title>
<meta name="robots" content="noindex, nofollow">
<meta name="revisit-after" content="365 days">
<meta http-equiv="imagetoolbar" content="no">
<script src="js/jquery-1.12.4.min.js"></script>
<script src="js/wwb12.min.js"></script>
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


<!-- Поместите код Google Analystics сюда -->
</head>
<body>
   <div id="ok-zapisLayer1">
      <div id="ok-zapisLayer1_Container">
         <div id="wb_indexImage33">
            <img src="images/vars_a1.png" id="indexImage33" alt=""></div>
         <div id="wb_Text3">
            <span id="wb_uid0"><strong>На ваш email отправленно письмо!</strong></span></div>
         <div id="wb_indexImage35">
            <img src="images/vars_a.png" id="indexImage35" alt=""></div>
         <div id="wb_indexText22">
            <span id="wb_uid1"><strong>ВЕРНУТЬСЯ НА САЙТ</strong></span></div>
         <div id="wb_indexShape5">
            <a href="./index.php" onmouseover="ShowObject('wb_indexImage35', 0);return false;" onmouseout="ShowObject('wb_indexImage33', 1);return false;"><img src="images/img0006.gif" id="indexShape5" alt=""></a></div>
      </div>
   </div>
   <div id="space"><br></div>
   <div id="container">
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
   <noscript><div><img src="https://mc.yandex.ru/watch/35205560" id="wb_uid2" alt=""/></div></noscript>

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
   </script><noscript><div id="wb_uid3">
         <img src="//top-fwz1.mail.ru/counter?id=2768139;js=na" height="1" width="1" alt="Рейтинг@Mail.ru"/>
      </div></noscript>
<!-- //Rating@Mail.ru counter --><!-- VKONTAKTE code -->
   <script>   (window.Image ? (new Image()) : document.createElement('img')).src = location.protocol + '//vk.com/rtrg?r=nMTeVKcFBlO4yb6DK64aTHVqgtHPBwPu7u0*0pbLKzuFhJAeotlzM0air2PcCvMd668MdqeGTIhg0FsTyQJCLNXPfJiR1jbXFa25jEJtfj/zVCee2IUa3vr*ikK55PvbARxSKu/TgXwn98*JHcZKBGLE50Tt8m4UqOZLEGQ5RK8-';
   </script>
<!-- VKONTAKTE code --><link href="http://fonts.googleapis.com/css?family=Roboto:400,900italic,900,700italic,700,500italic,500,400italic,300italic,300,100italic,100&subset=latin,cyrillic-ext,cyrillic" rel="stylesheet">
<link href="css/ok-email.css" rel="stylesheet">
<link rel="shortcut icon" href="favicon.ico">
<link rel="icon" type="image/gif" href="animated_favicon1.gif">
</body>
</html>