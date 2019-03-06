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
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['formid']) && $_POST['formid'] == 'indexform1')
{
   $mailto = 'zakaz@nsk-ohrana.ru';
   $mailfrom = isset($_POST['email']) ? $_POST['email'] : $mailto;
   $subject = 'Kalk nsk-ohrana.ru';
   $message = '';
   $success_url = './ok-kalk.php';
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
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['formid']) && $_POST['formid'] == 'indexform2')
{
   $mailto = 'zakaz@nsk-ohrana.ru';
   $mailfrom = isset($_POST['email']) ? $_POST['email'] : $mailto;
   $subject = 'Email price nsk-ohrana.ru';
   $message = '';
   $success_url = './ok-email.php';
   $error_url = './ok-error.php';
   $error = '';
   $autoresponder_from = 'zakaz@nsk-ohrana.ru';
   $autoresponder_to = isset($_POST['email']) ? $_POST['email'] : $mailfrom;
   $autoresponder_subject = 'Прайс лист услуг охранного агентства Берсерк';
   $autoresponder_message = 'Скачать актуальный прайс листможно по следующей ссылке:
https://yadi.sk/i/bhekz3-7qwGx3

C уважением команда охранного агентства Берсерк
сайт: nsk-ohrana.ru
email: zakaz@nsk-ohrana.ru
тел: 8 (383) 380-45-39';
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
   $autoresponder_header  = 'From: '.$autoresponder_from.$eol;
   $autoresponder_header .= 'Reply-To: '.$autoresponder_from.$eol;
   $autoresponder_header .= 'MIME-Version: 1.0'.$eol;
   $autoresponder_header .= 'Content-Type: text/plain; charset=UTF-8'.$eol;
   $autoresponder_header .= 'Content-Transfer-Encoding: 8bit'.$eol;
   $autoresponder_header .= 'X-Mailer: PHP v'.phpversion().$eol;
   mail($autoresponder_to, $autoresponder_subject, $autoresponder_message, $autoresponder_header);
   header('Location: '.$success_url);
   exit;
}
?>
<!doctype html>
<html lang="ru">
<head>
<meta charset="utf-8">
<title>БЕРСЕРК - Охранное агентство в Новосибирске | Мы дарим скидку 10% для всех новых клиентов</title>
<meta name="description" content="Охранное агентство Берсерк в Новосибирске. Индивидуальный подбор охранников, Физическая защита ваших интересов. Наши цены ниже на 30%!">
<script src="js/jquery-1.12.4.min.js"></script>
<!-- <script src="js/wb.validation.min.js"></script> -->

<script src="fancybox/jquery.easing-1.3.pack.js"></script>
<script src="fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<script src="fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
<script>
   function ValidateindexForm1(theForm)
   {
      var regexp;
      regexp = /^([0-9a-z]([-.\w]*[0-9a-z])*@(([0-9a-z])+([-\w]*[0-9a-z])*\.)+[a-z]{2,6})$/i;
      if (!regexp.test(theForm.indexEditbox1.value))
      {
         alert(" Введите email");
         theForm.indexEditbox1.focus();
         return false;
      }
      if (theForm.indexEditbox1.value == "")
      {
         alert(" Введите email");
         theForm.indexEditbox1.focus();
         return false;
      }
      return true;
   }
</script>
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
      $("#indexForm1").submit(function(event)
      {
         var isValid = $.validate.form(this);
         return isValid;
      });
      $("#indexEditbox44").validate(
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
         font_size: '13px',
         position: 'topleft',
         offsetx: 0,
         offsety: -48,
         effect: 'fade',
         error_text: 'Проверьте номер: мобильный 89133456789 или городской 83831234567'
      });
      $("#indexjQueryButton4").button();
      $("a[href*='#kontakti']").click(function(event)
      {
         event.preventDefault();
         $('html, body').stop().animate({ scrollTop: $('#wb_kontakti').offset().top }, 600, 'linear');
      });
      $("a[data-rel='indexPhotoGallery1']").attr('rel', 'indexPhotoGallery1');
      $("a[rel^='indexPhotoGallery1']").fancybox({});
      $("a[data-rel='indexPhotoGallery2']").attr('rel', 'indexPhotoGallery2');
      $("a[rel^='indexPhotoGallery2']").fancybox({width:980,
   height:480});
      $("a[href*='#stoimost']").click(function(event)
      {
         event.preventDefault();
         $('html, body').stop().animate({ scrollTop: $('#wb_stoimost').offset().top }, 600, 'linear');
      });
      $("a[href*='#otzivi']").click(function(event)
      {
         event.preventDefault();
         $('html, body').stop().animate({ scrollTop: $('#wb_otzivi').offset().top }, 600, 'linear');
      });
      $("a[href*='#sert']").click(function(event)
      {
         event.preventDefault();
         $('html, body').stop().animate({ scrollTop: $('#wb_sert').offset().top }, 600, 'linear');
      });
      $("a[href*='#yslygi']").click(function(event)
      {
         event.preventDefault();
         $('html, body').stop().animate({ scrollTop: $('#wb_yslygi').offset().top }, 600, 'linear');
      });
   });
</script>



</head>
<body>
   <div id="indexLayer1">
      <div id="indexLayer1_Container">
      </div>
   </div>
   <div id="indexLayer3">
      <div id="indexLayer3_Container">
         <div id="wb_indexShape13">
            <img src="images/img0038.png" id="indexShape13" alt=""></div>
         <div id="wb_indexForm1">
            <form name="indexForm1" method="post" action="<?php echo basename(__FILE__); ?>" enctype="multipart/form-data" accept-charset="UTF-8" target="_self" id="indexForm1" onsubmit="return ValidateindexForm1(this)">
               <input type="hidden" name="formid" value="indexform1">
               <input type="hidden" name="Время отправки заявки" value="<?=$t?>">
               <input type="hidden" name="Ключевик" value="<?=$utmdata?>">
               <input type="hidden" name="Источник" value="<?=$referer?>">
               <input type="tel" id="indexEditbox44" name="тел" value="" autocomplete="off" spellcheck="false" placeholder=" 89131234567*">
               <input type="text" id="indexEditbox6" name="имя" value="" autocomplete="off" spellcheck="false" placeholder=" &#1042;&#1074;&#1077;&#1076;&#1080;&#1090;&#1077; &#1080;&#1084;&#1103;">
               <div id="wb_indexImage11">
                  <img src="images/arrow3-150x150-black-left9.png" id="indexImage11" alt=""></div>
               <select name="Количество охранников" size="1" id="indexCombobox3">
                  <option value="1">1</option>
                  <option value="2">2</option>
                  <option value="3">3</option>
                  <option value="4">4</option>
                  <option value="5">5</option>
                  <option value="6">6</option>
                  <option value="7">7</option>
                  <option value="8">8</option>
                  <option value="9">9</option>
               </select>
               <input type="radio" id="indexRadioButton9" name="Возраст" value="46-55">
               <input type="radio" id="indexRadioButton1" name="Возраст" value="36-45">
               <input type="radio" id="indexRadioButton2" name="Возраст" value="27-35">
               <input type="radio" id="indexRadioButton3" name="Возраст" value="21-26" checked="">
               <select name="Пол" size="1" id="indexCombobox1">
                  <option value="Мужчина">Мужчина</option>
                  <option value="Женщина">Женщина</option>
               </select>
               <div id="wb_indexText53">
                  <span id="wb_uid67"><strong>03. КОЛИЧЕСТВО ОХРАННИКОВ:</strong></span></div>
               <div id="wb_indexImage12">
                  <img src="images/eco_a.png" id="indexImage12" alt=""></div>
               <div id="wb_failImage4">
                  <img src="images/eco_a1.png" id="failImage4" alt=""></div>
               <div id="wb_indexText16">
                  <span id="wb_uid68"><strong>ПОЛУЧИТЬ РАСЧЕТ</strong></span></div>
               <button type="submit" id="indexjQueryButton1" onmouseover="ShowObject('wb_indexImage12', 0);return false;" onmouseout="ShowObject('wb_indexImage12', 1);return false;" name="" value="Получить расчет">Получить расчет</button>
               <select name="Разряд охранника" size="1" id="indexCombobox2">
                  <option value="4 - Разряд (с использованием специальных средств)">4 - Разряд (с использованием специальных средств)</option>
                  <option value="5 - Разряд (с использованием травматического оружия)">5 - Разряд (с использованием травматического оружия)</option>
                  <option value="6 - Разряд (с использованием служебного оружия)">6 - Разряд (с использованием служебного оружия)</option>
               </select>
               <select name="Классификация объекта" size="1" id="indexCombobox4">
                  <option value="Пост охраны в бизнес-центре, торгово-развлекательном центре, офисе и т.д.">Пост охраны в бизнес-центре, торгово-развлекательном центре, офисе и т.д.</option>
                  <option value="Пост охраны в магазине">Пост охраны в магазине</option>
                  <option value="Пост охраны в жилом доме, коттеджном или дачном поселке">Пост охраны в жилом доме, коттеджном или дачном поселке</option>
                  <option value="Пост охраны на строительном объекте">Пост охраны на строительном объекте</option>
                  <option value="Пост охраны на складском или производственном комплексе">Пост охраны на складском или производственном комплексе</option>
                  <option value="Пост охраны в предприятиях общественного питания (кафе, рестораны, бары и т.д.)">Пост охраны в предприятиях общественного питания (кафе, рестораны, бары и т.д.)</option>
                  <option value="Пост охраны на автостоянке">Пост охраны на автостоянке</option>
                  <option value="Пост охраны в финансовом учреждении">Пост охраны в финансовом учреждении</option>
               </select>
               <input type="radio" id="indexRadioButton5" name="РЕЖИМ РАБОТЫ" value="24" checked="">
               <input type="radio" id="indexRadioButton4" name="РЕЖИМ РАБОТЫ" value="12">
            </form>
         </div>
         <div id="wb_indexText52">
            <span id="wb_uid69"><strong>01. ПОЛ ОХРАННИКА:</strong></span></div>
         <div id="wb_indexText25">
            <span id="wb_uid70"><strong>02. ВОЗРАСТ:</strong></span></div>
         <div id="wb_indexText57">
            <span id="wb_uid71">27-35</span></div>
         <div id="wb_failText1">
            <span id="wb_uid72">21-26</span></div>
         <div id="wb_indexText26">
            <span id="wb_uid73">36-45</span></div>
         <div id="wb_indexText27">
            <span id="wb_uid74">46-55</span></div>
         <div id="wb_indexText28">
            <span id="wb_uid75">Суточный (24 часа пост)</span></div>
         <div id="wb_indexText29">
            <span id="wb_uid76"><strong>05. РЕЖИМ РАБОТЫ:</strong></span></div>
         <div id="wb_indexText60">
            <span id="wb_uid77"><strong>04. РАЗРЯД ОХРАННИКА:</strong></span></div>
         <div id="wb_indexText35">
            <span id="wb_uid78"><strong>06. КЛАССИФИКАЦИЯ ОБЪЕКТА:</strong></span></div>
         <div id="wb_indexText54">
            <span id="wb_uid79"><strong>&#1055;&#1086;&#1083;&#1091;&#1095;&#1080;&#1090;&#1100; точный &#1088;&#1072;&#1089;&#1095;&#1077;&#1090; &#1089;&#1090;&#1086;&#1080;&#1084;&#1086;&#1089;&#1090;&#1080; для &#1042;&#1072;&#1096;&#1077;&#1075;&#1086; охранника</strong></span></div>
         <div id="wb_failText4">
            <span id="wb_uid80"><strong>УЗНАЙТЕ СТОИМОСТЬ<br> </strong></span><span id="wb_uid81"><strong>ВАШЕГО ОХРАННИКА</strong></span></div>
         <div id="wb_indexImage13">
            <img src="images/pravo.png" id="indexImage13" alt=""></div>
         <div id="wb_indexText34">
            <span id="wb_uid82">Полусуточный (12 часов пост)</span></div>
      </div>
   </div>
   <div id="indexLayer7">
      <div id="indexLayer7_Container">
         <div id="wb_indexText19">
            <span id="wb_uid83"><strong>СКИДКА ДО 10% НА ОХРАНУ ОБЪЕКТОВ</strong></span></div>
         <div id="wb_indexText15">
            <span id="wb_uid84"><strong>КАЖДЫЙ 2-ОЙ МЕСЯЦ КВАРТАЛА </strong></span></div>
         <div id="wb_sert">
            <a id="sert">&nbsp;</a>
         </div>
      </div>
   </div>
   <div id="failLayer1">
      <div id="failLayer1_Container">
         <div id="wb_indexImage5">
            <img src="images/security-1.png" id="indexImage5" alt=""></div>
         <div id="wb_failImage6">
            <img src="images/egg_ico_6.png" id="failImage6" alt=""></div>
         <div id="wb_failImage12">
            <img src="images/egg_ico_4.png" id="failImage12" alt=""></div>
         <div id="wb_failImage13">
            <img src="images/egg_ico_3.png" id="failImage13" alt=""></div>
         <div id="wb_failImage14">
            <img src="images/egg_ico_2.png" id="failImage14" alt=""></div>
         <div id="wb_failImage15">
            <img src="images/egg_ico_1.png" id="failImage15" alt=""></div>
         <div id="wb_indexText8">
            <span id="wb_uid85"><strong>ПОЛНАЯ МАТЕРИАЛЬНАЯ ОТВЕТСТВЕННОСТЬ</strong></span></div>
         <div id="wb_failText8">
            <span id="wb_uid86"><strong>&#1041;ЕСПЛАТНАЯ КОНСУЛЬТАЦИЯ</strong></span></div>
         <div id="wb_failText10">
            <span id="wb_uid87">Бывшие сотрудники элитных силовых ведомств со связями в гос. службах. Мы сотрудничаем с МВД.</span></div>
         <div id="wb_indexText44">
            <span id="wb_uid88">Мы страхуем свою ответственность перед вами на 10 000 000 рублей. При необходимости, размер страховой суммы может быть увеличен. </span></div>
         <div id="wb_failText14">
            <span id="wb_uid89"><strong>ГАРАНТИЯ КОНФИДЕНЦИАЛЬНОСТИ</strong></span></div>
         <div id="wb_failText15">
            <span id="wb_uid90"><strong>ИНДИВИДУАЛЬНЫЙ ПОДБОР ОХРАННИКОВ</strong></span></div>
         <div id="wb_failText16">
            <span id="wb_uid91">Бесплатно составим акт обследования объекта, поможем подготовить документы</span></div>
         <div id="wb_failText9">
            <span id="wb_uid92">Индивидуальный подбор охранников и формы одежды. С учетом требований охраняемого мероприятия, объекта или пожеланий заказчика </span></div>
         <div id="wb_failImage7">
            <img src="images/egg_ico_5.png" id="failImage7" alt=""></div>
         <div id="wb_failText12">
            <span id="wb_uid93"><strong>ЦЕНА НА 30% НИЖЕ РЫНОЧНОЙ.</strong></span></div>
         <div id="wb_failText11">
            <span id="wb_uid94">&#1055;одберем решение под любой кошелек</span></div>
         <div id="wb_failText5">
            <span id="wb_uid95"><strong> </strong></span><span id="wb_uid96"><strong>УЖЕ </strong></span><span id="wb_uid97"><strong><br></strong></span><span id="wb_uid98"><strong> 1825 </strong></span><span id="wb_uid99"><strong><br></strong></span><span id="wb_uid100"><strong> КЛИЕНТОВ <br> ВЫБРАЛИ НАС!</strong></span><span id="wb_uid101"><strong> </strong></span></div>
         <div id="wb_failText13">
            <span id="wb_uid102"><strong>НАШИ СОТРУДНИКИ - НАСТОЯЩИЕ ПРОФЕССИОНАЛЫ</strong></span></div>
         <div id="wb_yslygi">
            <a id="yslygi">&nbsp;</a>
         </div>
         <div id="wb_failText19">
            <span id="wb_uid103">Соблюдаем конфиденциальность в отношении информации о Заказчике, его коммерческой деятельности, финансовом положении, заключенных договорах и партнерах.</span></div>
      </div>
   </div>
   <div id="indexLayer6">
      <div id="indexLayer6_Container">
         <div id="wb_indexText91">
            <span id="wb_uid104"><strong>ЕСТЬ ВОПРОСЫ?<br></strong></span><span id="wb_uid105"><strong>МЫ ГОТОВЫ НА НИХ ОТВЕТИТЬ!</strong></span></div>
         <div id="wb_indexBulletedList2">
            <div>
               <div class="bullet" id="wb_uid106">&bull;</div>
               <div class="item item0"><span id="wb_uid107">Нужна физическая защита?<br></span></div>
            </div>
            <div id="wb_uid108">
               <div class="bullet" id="wb_uid109">&bull;</div>
               <div class="item item1"><span id="wb_uid110">Боитесь за сохранность имущества?<br></span></div>
            </div>
            <div id="wb_uid111">
               <div class="bullet" id="wb_uid112">&bull;</div>
               <div class="item item2"><span id="wb_uid113">Необходимо получить информацию о бизнес партнере?<br></span></div>
            </div>
         </div>
         <div id="wb_kontakti">
            <a id="kontakti">&nbsp;</a>
         </div>
         <div id="wb_indexText87">
            <span id="wb_uid114">&#1047;&#1072;&#1076;&#1072;&#1081;&#1090;&#1077; &#1074;&#1086;&#1087;&#1088;&#1086;&#1089;, &#1085;&#1072;&#1096;&#1080; специалисты<br>&#1086;&#1090;&#1074;&#1077;&#1090;&#1103;&#1090; &#1085;&#1072; &#1085;&#1077;&#1075;&#1086; &#1089;&#1086;&#1074;&#1077;&#1088;&#1096;&#1077;&#1085;&#1085;&#1086; &#1073;&#1077;&#1089;&#1087;&#1083;&#1072;&#1090;&#1085;&#1086;!<br></span><span id="wb_uid115">&#1048;&#1083;&#1080; &#1079;&#1074;&#1086;&#1085;&#1080;&#1090;&#1077; &#1085;&#1072;&#1084; по <strong>тел: </strong></span><span id="wb_uid116">+7 (953) 895-30-70</span><span id="wb_uid117"> &#1080; &#1079;&#1072;&#1076;&#1072;&#1074;&#1072;&#1081;&#1090;&#1077; &#1074;&#1086;&#1087;&#1088;&#1086;&#1089; &#1083;&#1080;&#1095;&#1085;&#1086;</span><span id="wb_uid118"><br></span></div>
         <div id="wb_страница2Text13">
            <span id="wb_uid119">Александр Анатольевич</span></div>
         <div id="wb_страница2Text12">
            <span id="wb_uid120">Руководитель отдела по работе с клиентами</span></div>
         <div id="wb_failImage9">
            <img src="images/vars_a1.png" id="failImage9" alt=""></div>
         <div id="wb_failImage10">
            <img src="images/vars_a.png" id="failImage10" alt=""></div>
         <div id="wb_indexText88">
            <span id="wb_uid121"><strong>ЗАДАТЬ ВОПРОС АЛЕКСАНДРУ</strong></span></div>
         <div id="wb_indexImage14">
            <img src="images/PQiTqIkbYHg.png" id="indexImage14" alt=""></div>
         <div id="wb_indexShape30">
            <a href="javascript:displaylightbox('./vopros.php',{width:425,height:475,scrolling:'no'})" target="_self" onmouseover="ShowObject('wb_failImage10', 0);return false;" onmouseout="ShowObject('wb_failImage10', 1);return false;"><img src="images/img0039.gif" id="indexShape30" alt=""></a></div>
      </div>
   </div>
   <div id="iindexLayer5">
      <div id="iindexLayer5_Container">
         <div id="wb_indexImage56">
            <img src="images/border.jpg" id="indexImage56" alt=""></div>
         <div id="wb_indexImage61">
            <img src="images/border.jpg" id="indexImage61" alt=""></div>
         <div id="wb_indexText46">
            <span id="wb_uid122"><strong>НАШИ </strong></span><span id="wb_uid123"><strong>СЕРТИФИКАТЫ</strong></span></div>
         <div id="wb_indexPhotoGallery1">
            <table id="indexPhotoGallery1">
               <tr>
                  <td class="thumbnail"><a href="images/1010.jpg" data-rel="indexPhotoGallery1" title="1010"><img alt="1010" id="indexPhotoGallery1_img0" src="images/tn_1010.jpg" title="1010"></a></td>
               </tr>
            </table>
         </div>
         <div id="wb_indexPhotoGallery2">
            <table id="indexPhotoGallery2">
               <tr>
                  <td class="thumbnail"><a href="images/22.jpg" data-rel="indexPhotoGallery2" title="22"><img alt="22" id="indexPhotoGallery2_img0" src="images/tn_22.jpg" title="22"></a></td>
               </tr>
            </table>
         </div>
      </div>
   </div>
   <div id="indexLayer2">
      <div id="indexLayer2_Container">
         <div id="wb_indexText32">
            <span id="wb_uid124">пресеченных попыток ограбления<br></span></div>
         <div id="wb_indexText31">
            <span id="wb_uid125">клиентов уже выбрали нас и остались довольны </span></div>
         <div id="wb_indexText33">
            <span id="wb_uid126">лет &#1091;&#1089;&#1087;&#1077;&#1096;&#1085;&#1099;&#1093;<br>&#1088;&#1072;&#1073;&#1086;&#1090; по<br>охране</span></div>
         <div id="wb_indexImage26">
            <img src="images/img0063.png" id="indexImage26" alt=""></div>
         <div id="wb_indexImage10">
            <img src="images/img0134.png" id="indexImage10" alt=""></div>
         <div id="wb_indexImage21">
            <img src="images/img0135.png" id="indexImage21" alt=""></div>
         <div id="wb_indexImage22">
            <img src="images/img0136.png" id="indexImage22" alt=""></div>
         <div id="wb_indexImage35">
            <img src="images/img0137.png" id="indexImage35" alt=""></div>
         <div id="wb_indexImage24">
            <img src="images/img0138.png" id="indexImage24" alt=""></div>
         <div id="wb_indexImage17">
            <img src="images/img0139.png" id="indexImage17" alt=""></div>
         <div id="wb_indexImage34">
            <img src="images/img0140.png" id="indexImage34" alt=""></div>
         <div id="wb_indexImage36">
            <img src="images/img0141.png" id="indexImage36" alt=""></div>
         <div id="wb_indexImage3">
            <img src="images/img0142.png" id="indexImage3" alt=""></div>
         <div id="wb_indexText30">
            <span id="wb_uid127">совершенных ограблений<br></span></div>
      </div>
   </div>
   <div id="indexLayer5">
      <div id="indexLayer5_Container">
<!-- otzivi -->
         <div id="indexHtml1">
            <a class="flamp-widget" href="http://novosibirsk.flamp.ru/firm/berserk_okhrannoe_predpriyatie-70000001021215631" data-flamp-widget-type="responsive" data-flamp-widget-count="3" data-flamp-widget-id="70000001021215631" data-flamp-widget-width="100%">Отзывы о нас на Флампе</a><script>            !function(d,s){var js,fjs=d.getElementsByTagName(s)[0];js=d.createElement(s);js.async=1;js.src="//widget.flamp.ru/loader.js";fjs.parentNode.insertBefore(js,fjs);}(document,"script");
            </script></div>
         <div id="wb_indexText9">
            <span id="wb_uid128"><strong>ОТЗЫВЫ </strong></span><span id="wb_uid129"><strong>НАШИХ КЛИЕНТОВ</strong></span></div>
      </div>
   </div>
   <div id="container">
      <div id="wb_indexImage7">
         <img src="images/section-10-man_HO8sKf6.png" id="indexImage7" alt="">
      </div>
      <input type="text" id="indexEditbox2" onclick="ShowObject('indexEditbox2', 0);ShowObject('indexEditbox6', 1);return false;" name="имя" value=" Введите имя" autocomplete="off" spellcheck="false">
      <input type="text" id="indexEditbox3" onclick="ShowObject('indexEditbox3', 0);ShowObject('indexEditbox44', 1);return false;" name="имя" value=" 89131234567*" autocomplete="off" spellcheck="false">
      <div id="wb_head">
         <a id="head">&nbsp;</a>
      </div>
      <div id="wb_indexShape26">
         <img src="images/img0064.png" id="indexShape26" alt="">
      </div>
      <div id="wb_indexShape23">
         <img src="images/img0101.png" id="indexShape23" alt="">
      </div>
      <div id="wb_indexShape8">
         <img src="images/img0095.png" id="indexShape8" alt="">
      </div>
      <div id="wb_indexShape14">
         <img src="images/img0096.png" id="indexShape14" alt="">
      </div>
      <div id="wb_indexShape11">
         <img src="images/img0084.png" id="indexShape11" alt="">
      </div>
      <div id="wb_indexShape16">
         <img src="images/img0087.png" id="indexShape16" alt="">
      </div>
      <div id="wb_indexShape18">
         <img src="images/img0083.png" id="indexShape18" alt="">
      </div>
      <div id="wb_indexShape17">
         <img src="images/img0088.png" id="indexShape17" alt="">
      </div>
      <div id="wb_indexShape3">
         <img src="images/img0060.png" id="indexShape3" alt="">
      </div>
      <div id="wb_index123Image2">
         <img src="images/btt1n.png" id="index123Image2" alt="">
      </div>
      <div id="wb_страница2Text8">
         <span id="wb_uid0"><strong>ПОЛНЫЙ СПЕКТР ОХРАННЫХ УСЛУГ В НОВОСИБИРСКЕ</strong></span>
      </div>
      <div id="wb_indexText4">
         <span id="wb_uid1">17 лет на рынке, полная конфиденциальность, страхуем ответственность, бесплатная консультация</span>
      </div>
      <div id="wb_failText2">
         <span id="wb_uid2"><strong>&#1041;&#1045;&#1057;&#1055;&#1051;&#1040;&#1058;&#1053;&#1054;&#1045;<br>&#1042;&#1067;&#1045;&#1047;&#1044;&#1053;&#1054;&#1045; &#1054;&#1041;&#1057;&#1051;&#1045;&#1044;&#1054;&#1042;&#1040;&#1053;&#1048;&#1045;<br>&#1048; &#1050;&#1054;&#1053;&#1057;&#1059;&#1051;&#1068;&#1058;&#1040;&#1062;&#1048;&#1071; &#1055;&#1054; &#1054;&#1041;&#1066;&#1045;&#1050;&#1058;&#1059;</strong></span>
      </div>
      <div id="wb_indexText6">
         <span id="wb_uid3">Вы получите, уже на следующий день, подробный проект охраны и расчет сметы в 3-х вариантах</span>
      </div>
      <div id="wb_indexImage6">
         <img src="images/img0037.png" id="indexImage6" alt="">
      </div>
      <div id="wb_indexText24">
         <span id="wb_uid4"><strong>НАШИ </strong></span><span id="wb_uid5"><strong>УСЛУГИ</strong></span>
      </div>
      <div id="wb_index123Image1">
         <img src="images/bttn.png" id="index123Image1" alt="">
      </div>
      <div id="wb_indexText36">
         <span id="wb_uid6"><strong>ВЫЗВАТЬ СПЕЦИАЛИСТА</strong></span>
      </div>
      <div id="wb_indexImage29">
         <img src="images/service_5.jpg" id="indexImage29" alt="">
      </div>
      <div id="wb_indexText18">
         <span id="wb_uid7"><strong>БЕЗОПАСНОСТЬ НА МЕРОПРИЯТИЯХ</strong></span>
      </div>
      <button type="submit" id="indexjQueryButton4" onmouseover="ShowObject('wb_indexImage3', 0);return false;" onmouseout="ShowObject('wb_indexImage3', 1);return false;" name="" value="Получить расчет">Получить расчет</button>
      <div id="wb_indexImage39">
         <img src="images/service_1.jpg" id="indexImage39" alt="">
      </div>
      <div id="wb_indexImage40">
         <img src="images/service_2.jpg" id="indexImage40" alt="">
      </div>
      <div id="wb_indexImage41">
         <img src="images/service_3.jpg" id="indexImage41" alt="">
      </div>
      <div id="wb_indexImage42">
         <img src="images/service_4.jpg" id="indexImage42" alt="">
      </div>
      <div id="wb_indexText23">
         <span id="wb_uid8"><strong>ОХРАНА ОБЪЕКТОВ</strong></span>
      </div>
      <div id="wb_indexText38">
         <span id="wb_uid9"><strong>ИНКАССАЦИЯ</strong></span>
      </div>
      <div id="wb_indexText39">
         <span id="wb_uid10"><strong>СОПРОВОЖДЕНИЕ ГРУЗОВ</strong></span>
      </div>
      <div id="wb_indexText42">
         <span id="wb_uid11"><strong>ТЕЛОХРАНИТЕЛИ</strong></span>
      </div>
      <div id="wb_indexImage66">
         <img src="images/service_6.jpg" id="indexImage66" alt="">
      </div>
      <div id="wb_indexText55">
         <span id="wb_uid12"><strong>УСТАНОВКА СКУД И ВИДЕОНАБЛЮДЕНИЯ</strong></span>
      </div>
      <div id="wb_indexText61">
         <span id="wb_uid13"><strong>ГРУППА БЫСТРОГО РЕАГИРОВАНИЯ</strong></span>
      </div>
      <div id="wb_indexImage75">
         <img src="images/eco_a1.png" id="indexImage75" alt="">
      </div>
      <div id="wb_indexImage76">
         <a href="javascript:displaylightbox('',{})" target="_self"><img src="images/eco_a.png" id="indexImage76" alt=""></a>
      </div>
      <div id="wb_indexText62">
         <span id="wb_uid14"><strong>ПОДРОБНЕЕ</strong></span>
      </div>
      <div id="wb_indexText63">
         <span id="wb_uid15"><strong>ПОЖАРНО - ОХРАННАЯ СИГНАЛИЗАЦИЯ</strong></span>
      </div>
      <div id="wb_indexText17">
         <span id="wb_uid16"><strong>от 100 руб/час</strong></span>
      </div>
      <div id="wb_indexText20">
         <span id="wb_uid17"><strong>от 900 руб/час</strong></span>
      </div>
      <div id="wb_indexText22">
         <span id="wb_uid18"><strong>от 150 руб/час</strong></span>
      </div>
      <div id="wb_indexText40">
         <span id="wb_uid19">(по всей России) </span>
      </div>
      <div id="wb_indexText41">
         <span id="wb_uid20"><strong>от 250 руб/час</strong></span>
      </div>
      <div id="wb_indexShape10">
         <img src="images/img0085.png" id="indexShape10" alt="">
      </div>
      <div id="wb_indexShape6">
         <img src="images/img0089.png" id="indexShape6" alt="">
      </div>
      <div id="wb_indexShape5">
         <a href="#" onmouseover="Toggle('wb_indexShape5', 'fade', 500);return false;" onmouseout="Toggle('wb_indexShape5', 'fade', 500);return false;"><img src="images/img0091.png" id="indexShape5" alt=""></a>
      </div>
      <div id="wb_indexShape2">
         <img src="images/img0092.png" id="indexShape2" alt="">
      </div>
      <div id="wb_indexShape21">
         <img src="images/img0097.png" id="indexShape21" alt="">
      </div>
      <div id="wb_indexShape22">
         <img src="images/img0099.png" id="indexShape22" alt="">
      </div>
      <div id="wb_indexShape25">
         <img src="images/img0102.png" id="indexShape25" alt="">
      </div>
      <div id="wb_indexShape27">
         <img src="images/img0104.png" id="indexShape27" alt="">
      </div>
      <div id="wb_indexText7">
         <span id="wb_uid21"><strong>ЕЖЕДНЕВНО ПОД </strong></span><span id="wb_uid22"><strong>НАШЕЙ ЗАЩИТОЙ</strong></span>
      </div>
      <div id="wb_indexShape7">
         <a href="#" onclick="ShowObject('', 1);return false;" onmouseover="Toggle('wb_indexShape27', 'fade', 300);return false;" onmouseout="Toggle('wb_indexShape27', 'fade', 300);return false;"><img src="images/img0105.gif" id="indexShape7" alt=""></a>
      </div>
      <div id="wb_indexShape24">
         <a href="#" onclick="ShowObject('', 1);return false;" onmouseover="Toggle('wb_indexShape25', 'fade', 300);return false;" onmouseout="Toggle('wb_indexShape25', 'fade', 300);return false;"><img src="images/img0103.gif" id="indexShape24" alt=""></a>
      </div>
      <div id="wb_indexShape9">
         <a href="#" onclick="ShowObject('', 1);return false;" onmouseover="Toggle('wb_indexShape22', 'fade', 300);return false;" onmouseout="Toggle('wb_indexShape22', 'fade', 300);return false;"><img src="images/img0100.gif" id="indexShape9" alt=""></a>
      </div>
      <div id="wb_indexShape20">
         <a href="#" onclick="ShowObject('', 1);return false;" onmouseover="Toggle('wb_indexShape21', 'fade', 300);return false;" onmouseout="Toggle('wb_indexShape21', 'fade', 300);return false;"><img src="images/img0098.gif" id="indexShape20" alt=""></a>
      </div>
      <div id="wb_indexShape12">
         <a href="#" onclick="ShowObject('', 1);return false;" onmouseover="Toggle('wb_indexShape10', 'fade', 300);return false;" onmouseout="Toggle('wb_indexShape10', 'fade', 300);return false;"><img src="images/img0086.gif" id="indexShape12" alt=""></a>
      </div>
      <div id="wb_indexShape19">
         <a href="#" onclick="ShowObject('', 1);return false;" onmouseover="Toggle('wb_indexShape6', 'fade', 300);return false;" onmouseout="Toggle('wb_indexShape6', 'fade', 300);return false;"><img src="images/img0090.gif" id="indexShape19" alt=""></a>
      </div>
      <div id="wb_indexShape15">
         <a href="#" onclick="PauseAudio('');return false;" onmouseover="Toggle('wb_indexShape5', 'fade', 300);return false;" onmouseout="Toggle('wb_indexShape5', 'fade', 300);return false;"><img src="images/img0093.gif" id="indexShape15" alt=""></a>
      </div>
      <div id="wb_indexShape4">
         <a href="#" onclick="StopAudio('');return false;" onmouseover="Toggle('wb_indexShape2', 'fade', 300);return false;" onmouseout="Toggle('wb_indexShape2', 'fade', 300);return false;"><img src="images/img0094.gif" id="indexShape4" alt=""></a>
      </div>
      <div id="wb_indexShape28">
         <img src="images/img0131.png" id="indexShape28" alt="">
      </div>
      <div id="wb_indexShape29">
         <img src="images/img0132.png" id="indexShape29" alt="">
      </div>
      <div id="wb_indexText67">
         <span id="wb_uid23"><strong>от 300 руб/час</strong></span>
      </div>
      <div id="wb_indexText71">
         <span id="wb_uid24"><strong>Согласно сметы</strong></span>
      </div>
      <div id="wb_indexText73">
         <span id="wb_uid25"><strong>от 8000 руб</strong></span>
      </div>
      <div id="wb_indexText74">
         <span id="wb_uid26">(проектирование и монтаж) </span>
      </div>
      <div id="wb_indexImage32">
         <img src="images/eco_a1.png" id="indexImage32" alt="">
      </div>
      <div id="wb_indexImage31">
         <img src="images/eco_a.png" id="indexImage31" alt="">
      </div>
      <div id="wb_indexText37">
         <span id="wb_uid27"><strong>УЗНАТЬ ПОДРОБНЕЕ</strong></span>
      </div>
      <div id="wb_failText3">
         <span id="wb_uid28">&#1044;&#1072;&#1085;&#1085;&#1099;&#1081; &#1080;&#1085;&#1090;&#1077;&#1088;&#1085;&#1077;&#1090;-&#1089;&#1072;&#1081;&#1090; &#1085;&#1086;&#1089;&#1080;&#1090; &#1080;&#1089;&#1082;&#1083;&#1102;&#1095;&#1080;&#1090;&#1077;&#1083;&#1100;&#1085;&#1086; &#1080;&#1085;&#1092;&#1086;&#1088;&#1084;&#1072;&#1094;&#1080;&#1086;&#1085;&#1085;&#1099;&#1081; &#1093;&#1072;&#1088;&#1072;&#1082;&#1090;&#1077;&#1088; &#1080; &#1085;&#1080; &#1087;&#1088;&#1080; &#1082;&#1072;&#1082;&#1080;&#1093; &#1091;&#1089;&#1083;&#1086;&#1074;&#1080;&#1103;&#1093; &#1085;&#1077; &#1103;&#1074;&#1083;&#1103;&#1077;&#1090;&#1089;&#1103; &#1087;&#1091;&#1073;&#1083;&#1080;&#1095;&#1085;&#1086;&#1081; &#1086;&#1092;&#1077;&#1088;&#1090;&#1086;&#1081;, &#1086;&#1087;&#1088;&#1077;&#1076;&#1077;&#1083;&#1103;&#1077;&#1084;&#1086;&#1081; &#1087;&#1086;&#1083;&#1086;&#1078;&#1077;&#1085;&#1080;&#1103;&#1084;&#1080; &#1057;&#1090;&#1072;&#1090;&#1100;&#1080; 437 (2) &#1043;&#1088;&#1072;&#1078;&#1076;&#1072;&#1085;&#1089;&#1082;&#1086;&#1075;&#1086; &#1082;&#1086;&#1076;&#1077;&#1082;&#1089;&#1072; &#1056;&#1060;.</span>
      </div>
      <div id="wb_indexText83">
         <span id="wb_uid29"><strong>НАШИ</strong></span><span id="wb_uid30"><strong> КОНТАКТЫ</strong></span>
      </div>
      <div id="wb_indexText43">
         <span id="wb_uid31">(за одного охранника) </span>
      </div>
      <div id="wb_stoimost">
         <a id="stoimost">&nbsp;</a>
      </div>
      <div id="wb_otzivi">
         <a id="otzivi">&nbsp;</a>
      </div>
      <div id="wb_indexText21">
         <span id="wb_uid32"><strong>от 600 руб/мес</strong></span>
      </div>
      <div id="wb_indexImage18">
         <img src="images/eco_a1.png" id="indexImage18" alt="">
      </div>
      <div id="wb_indexImage19">
         <img src="images/eco_a.png" id="indexImage19" alt="">
      </div>
      <div id="wb_indexText51">
         <span id="wb_uid33"><strong>ПОДРОБНЕЕ</strong></span>
      </div>
      <div id="wb_indexImage20">
         <img src="images/eco_a1.png" id="indexImage20" alt="">
      </div>
      <div id="wb_indexImage23">
         <img src="images/eco_a.png" id="indexImage23" alt="">
      </div>
      <div id="wb_indexText56">
         <span id="wb_uid34"><strong>ПОДРОБНЕЕ</strong></span>
      </div>
      <div id="wb_indexImage25">
         <img src="images/eco_a1.png" id="indexImage25" alt="">
      </div>
      <div id="wb_indexImage27">
         <img src="images/eco_a.png" id="indexImage27" alt="">
      </div>
      <div id="wb_indexText58">
         <span id="wb_uid35"><strong>ПОДРОБНЕЕ</strong></span>
      </div>
      <div id="wb_indexImage28">
         <img src="images/eco_a1.png" id="indexImage28" alt="">
      </div>
      <div id="wb_indexImage30">
         <img src="images/eco_a.png" id="indexImage30" alt="">
      </div>
      <div id="wb_indexText47">
         <span id="wb_uid36"><strong>ПОДРОБНЕЕ</strong></span>
      </div>
      <div id="wb_indexImage37">
         <img src="images/eco_a1.png" id="indexImage37" alt="">
      </div>
      <div id="wb_indexImage38">
         <img src="images/eco_a.png" id="indexImage38" alt="">
      </div>
      <div id="wb_indexText48">
         <span id="wb_uid37"><strong>ПОДРОБНЕЕ</strong></span>
      </div>
      <div id="wb_indexImage43">
         <img src="images/eco_a1.png" id="indexImage43" alt="">
      </div>
      <div id="wb_indexImage44">
         <img src="images/eco_a.png" id="indexImage44" alt="">
      </div>
      <div id="wb_indexText49">
         <span id="wb_uid38"><strong>ПОДРОБНЕЕ</strong></span>
      </div>
      <div id="wb_indexImage45">
         <img src="images/eco_a1.png" id="indexImage45" alt="">
      </div>
      <div id="wb_indexImage46">
         <img src="images/eco_a.png" id="indexImage46" alt="">
      </div>
      <div id="wb_indexText50">
         <span id="wb_uid39"><strong>ПОДРОБНЕЕ</strong></span>
      </div>
      <div id="wb_indexjQueryButton2">
         <a class="ui-button ui-widget ui-corner-all" href="javascript:displaylightbox('./vizov.php',{width:400,height:414,scrolling:'no'})" target="_self" id="indexjQueryButton2" onmouseover="ShowObject('wb_index123Image1', 0);return false;" onmouseout="ShowObject('wb_index123Image1', 1);return false;">Получить расчет</a>
      </div>
      <div id="wb_indexForm2">
         <form name="indexForm1" method="post" action="<?php echo basename(__FILE__); ?>" enctype="multipart/form-data" accept-charset="UTF-8" target="_self" id="indexForm2" onsubmit="return ValidateindexForm1(this)">
            <input type="hidden" name="formid" value="indexform2">
            <div id="wb_indexImage4">
               <img src="images/eco_a1.png" id="indexImage4" alt=""></div>
            <div id="wb_indexImage65">
               <img src="images/eco_a.png" id="indexImage65" alt=""></div>
            <div id="wb_indexText14">
               <span id="wb_uid40"><strong>ПОЛУЧИТЬ ПРАЙС ЛИСТ</strong></span></div>
            <div id="wb_indexText81">
               <span id="wb_uid41">Прайс лист &#1074;&#1089;&#1077;&#1093; услуг &#1072;&#1074;&#1090;&#1086;&#1084;&#1072;&#1090;&#1080;&#1095;&#1077;&#1089;&#1082;&#1080; &#1087;&#1088;&#1080;&#1076;&#1077;&#1090; &#1085;&#1072; &#1091;&#1082;&#1072;&#1079;&#1072;&#1085;&#1085;&#1099;&#1081; email &#1072;&#1076;&#1088;&#1077;&#1089; </span></div>
            <button type="submit" id="indexjQueryButton5" onmouseover="ShowObject('wb_indexImage65', 0);return false;" onmouseout="ShowObject('wb_indexImage65', 1);return false;" name="" value="Получить расчет">Получить расчет</button>
            <input type="email" id="indexEditbox1" name="email" value="" autocomplete="off" spellcheck="false" placeholder=" &#1042;&#1074;&#1077;&#1076;&#1080;&#1090;&#1077; email">
            <input type="text" id="indexEditbox4" onclick="ShowObject('indexEditbox4', 0);ShowObject('indexEditbox1', 1);return false;" name="email1" value=" Введите email" autocomplete="off" spellcheck="false">
         </form>
      </div>
      <div id="wb_indexText77">
         <span id="wb_uid42">Создани&#1077; &#1089;&#1072;&#1081;&#1090;&#1072;, продвижение</span>
      </div>
      <div id="wb_indexImage9">
         <img src="images/logo.png" id="indexImage9" alt="">
      </div>
      <div id="wb_indexShape31">
         <a href="http://medialife5.ru/?utm_source=portfolio&utm_medium=landing&utm_campaign=nsk-ohrana.ru" target="_blank"><img src="images/img0005.png" id="indexShape31" alt=""></a>
      </div>
      <div id="wb_indexText13">
         <span id="wb_uid43"><strong>ПОРЕКОМЕНДУЙ НАС СВОИМ<br> ДРУЗЬЯМ И ПОЛУЧИ <br></strong></span><span id="wb_uid44"><strong>10%</strong></span><span id="wb_uid45"><strong> ВОЗНАГРАЖДЕНИЯ</strong></span>
      </div>
      <div id="wb_indexText11">
         <span id="wb_uid46"><strong>Агентские вознаграждения до 50 000 руб</strong></span>
      </div>
      <div id="wb_indexText10">
         <span id="wb_uid47"><strong>Специальное предложение</strong></span>
      </div>
      <div id="wb_indexText12">
         <span id="wb_uid48"><strong>Все данные строго конфиденциальны</strong></span>
      </div>
      <div id="wb_indexjQueryButton6">
         <a class="ui-button ui-widget ui-corner-all" href="javascript:displaylightbox('./specpred.php',{width:400,height:414,scrolling:'no'})" target="_self" id="indexjQueryButton6" onmouseover="ShowObject('wb_indexImage31', 0);return false;" onmouseout="ShowObject('wb_indexImage31', 1);return false;">Получить расчет</a>
      </div>
      <div id="wb_indexjQueryButton9">
         <a class="ui-button ui-widget ui-corner-all" href="javascript:displaylightbox('./inkas.php',{width:736,height:390,scrolling:'no'})" target="_self" id="indexjQueryButton9" onmouseover="ShowObject('wb_indexImage38', 0);return false;" onmouseout="ShowObject('wb_indexImage38', 1);return false;">Получить расчет</a>
      </div>
      <div id="wb_indexjQueryButton7">
         <a class="ui-button ui-widget ui-corner-all" href="javascript:displaylightbox('./obekt.php',{width:736,height:390,scrolling:'no'})" target="_self" id="indexjQueryButton7" onmouseover="ShowObject('wb_indexImage30', 0);return false;" onmouseout="ShowObject('wb_indexImage30', 1);return false;">Получить расчет</a>
      </div>
      <div id="wb_indexjQueryButton10">
         <a class="ui-button ui-widget ui-corner-all" href="javascript:displaylightbox('./soprovojdenie.php',{width:736,height:390,scrolling:'no'})" target="_self" id="indexjQueryButton10" onmouseover="ShowObject('wb_indexImage44', 0);return false;" onmouseout="ShowObject('wb_indexImage44', 1);return false;">Получить расчет</a>
      </div>
      <div id="wb_indexjQueryButton11">
         <a class="ui-button ui-widget ui-corner-all" href="javascript:displaylightbox('./telohraniteli.php',{width:736,height:390,scrolling:'no'})" target="_self" id="indexjQueryButton11" onmouseover="ShowObject('wb_indexImage46', 0);return false;" onmouseout="ShowObject('wb_indexImage46', 1);return false;">Получить расчет</a>
      </div>
      <div id="wb_indexjQueryButton8">
         <a class="ui-button ui-widget ui-corner-all" href="javascript:displaylightbox('./meroprijatija.php',{width:736,height:390,scrolling:'no'})" target="_self" id="indexjQueryButton8" onmouseover="ShowObject('wb_indexImage19', 0);return false;" onmouseout="ShowObject('wb_indexImage19', 1);return false;">Получить расчет</a>
      </div>
      <div id="wb_indexjQueryButton13">
         <a class="ui-button ui-widget ui-corner-all" href="javascript:displaylightbox('./videonab.php',{width:736,height:390,scrolling:'no'})" target="_self" id="indexjQueryButton13" onmouseover="ShowObject('wb_indexImage27', 0);return false;" onmouseout="ShowObject('wb_indexImage27', 1);return false;">Получить расчет</a>
      </div>
      <div id="wb_indexjQueryButton12">
         <a class="ui-button ui-widget ui-corner-all" href="javascript:displaylightbox('./gbr.php',{width:736,height:390,scrolling:'no'})" target="_self" id="indexjQueryButton12" onmouseover="ShowObject('wb_indexImage23', 0);return false;" onmouseout="ShowObject('wb_indexImage23', 1);return false;">Получить расчет</a>
      </div>
      <div id="wb_indexjQueryButton3">
         <a class="ui-button ui-widget ui-corner-all" href="javascript:displaylightbox('./pozh_signal.php',{width:736,height:390,scrolling:'no'})" target="_self" id="indexjQueryButton3" onmouseover="ShowObject('wb_indexImage76', 0);return false;" onmouseout="ShowObject('wb_indexImage76', 1);return false;">Получить расчет</a>
      </div>
   </div>
   <div id="Layer13">
      <div id="Layer13_Container">
         <div id="wb_Text73">
            <span id="wb_uid49"><a href="#otzivi" class="style7">Отзывы</a>&nbsp; </span><span id="wb_uid50"> | </span></div>
         <div id="wb_indexText3">
            <span id="wb_uid51"><a href="#yslygi" class="style7">Наши услуги</a>&nbsp;&nbsp; | </span></div>
         <div id="wb_Text70">
            <span id="wb_uid52"><a href="#sert" class="style7">Сертификаты</a>&nbsp; </span><span id="wb_uid53"> | </span></div>
         <div id="wb_indexText79">
            <span id="wb_uid54"><a href="#kontakti" class="style7">Контакты</a></span><span id="wb_uid55"> </span></div>
         <div id="wb_Text58">
            <span id="wb_uid56"><strong><a href="tel:+7 (953) 895-30-70" class="style7">+7 (953) 895-30-70</a></strong></span></div>
         <div id="wb_indexImage1">
            <img src="images/s_a1.png" id="indexImage1" alt=""></div>
         <div id="wb_indexImage2">
            <img src="images/s_a.png" id="indexImage2" alt=""></div>
         <div id="wb_indexText70">
            <span id="wb_uid57"><a href="#stoimost" class="style7">Подбор охранника</a>&nbsp;&nbsp; | </span></div>
         <div id="wb_indexText2">
            <span id="wb_uid58">&#1086;&#1093;&#1088;&#1072;&#1085;&#1085;&#1086;&#1077; агентство</span></div>
         <div id="wb_indexText5">
            <span id="wb_uid59">ЗАКАЗАТЬ ЗВОНОК</span></div>
         <div id="wb_indexText1">
            <span id="wb_uid60">БЕРСЕРК</span></div>
         <div id="wb_indexShape1">
            <a href="javascript:displaylightbox('./callback.php',{width:400,height:414,scrolling:'no'})" target="_self" onmouseover="ShowObject('wb_indexImage2', 0);return false;" onmouseout="ShowObject('wb_indexImage2', 1);return false;"><img src="images/img0071.gif" id="indexShape1" alt=""></a></div>
         <div id="wb_indexShape32">
            <a href="#head"><img src="images/img0062.gif" id="indexShape32" alt=""></a></div>
      </div>
   </div>
   <div id="indexLayer8">
<!-- Карта яндекса -->
      <div id="indexHtml2">
         <script charset="utf-8" src="https://api-maps.yandex.ru/services/constructor/1.0/js/?sid=ja5thpLzIF2F0ta06ons3ScMbiIqJO2I&width=100%&height=330&lang=ru_RU&sourceType=constructor"></script>
</div>
      <div id="indexLayer4">
         <div id="indexLayer4_Container">
            <div id="wb_indexImage8">
               <img src="images/contacts.png" id="indexImage8" alt=""></div>
            <div id="wb_indexText89">
               <span id="wb_uid61"><strong>Адрес:</strong> </span><span id="wb_uid62">ул. Кирзаводская 11, 3 этаж<br></span><span id="wb_uid63"><strong>&nbsp;&nbsp;&nbsp;&nbsp; тел:</strong> +7 (953) 895-30-70</span><span id="wb_uid64"><br></span><span id="wb_uid65"><strong>e-mail:</strong> <a href="mailto:zakaz@nsk-ohrana.ru">oa-grup@ya.ru</a></span></div>
            <div id="wb_indexText90">
               <span id="wb_uid66"><strong>&#1053;&#1086;&#1074;&#1086;&#1089;&#1080;&#1073;&#1080;&#1088;&#1089;&#1082;</strong></span></div>
         </div>
      </div>
   </div>


<link href="css/wb.validation.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Roboto:400,900italic,900,700italic,700,500italic,500,400italic,300italic,300,100italic,100&subset=latin,cyrillic-ext,cyrillic" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=PT+Sans+Narrow:400,700&subset=latin,cyrillic,cyrillic-ext" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=PT+Sans:400,700italic,700,400italic&subset=latin,cyrillic-ext,cyrillic" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Roboto+Condensed:400,300,300italic,400italic,700,700italic&subset=latin,cyrillic" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Ubuntu+Condensed" rel="stylesheet">
<link href="css/index.css" rel="stylesheet">
<link rel="stylesheet" href="fancybox/jquery.fancybox-1.3.4.css">

</body>
</html>
