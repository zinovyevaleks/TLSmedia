$(function() {

  // Custom JS

  //Плавная прокрутка до якоря
  $(".anchor_link").click(function() {
    var _href = $(this).attr("href");
    $("html, body").animate({
      scrollTop: $(_href).offset().top + "px"
    });
    return false;
  });

  // Переключение показателей кейсов
  $('#headingOne').click(function() {
    if ($('#collapseOne').hasClass('show')) {
      $('#casesTabOne').removeClass('show');
    } else {
      $('#casesTabTwo').removeClass('show');
      $('#casesTabThree').removeClass('show');
      $('#casesTabOne').addClass('show')
    }
  });

  $('#headingTwo').click(function() {
    if ($('#collapseTwo').hasClass('show')) {
      $('#casesTabTwo').removeClass('show');
    } else {
      $('#casesTabOne').removeClass('show');
      $('#casesTabThree').removeClass('show');
      $('#casesTabTwo').addClass('show')
    }
  });

  $('#headingThree').click(function() {
    if ($('#collapseThree').hasClass('show')) {
      $('#casesTabThree').removeClass('show');
    } else {
      $('#casesTabOne').removeClass('show');
      $('#casesTabTwo').removeClass('show');
      $('#casesTabThree').addClass('show')
    }
  });

  // Переключение картинок по радиокнопкам

  $('.pane-right-radio').click(function() {
    var radiovalue = $(this).val();
    $(".pane-left-img").hide();
    $("#img" + radiovalue).show();
  });


  // Переключение цветов активных хэдеров карточек в Аудите

  $('.audit_card_header').click(function() {
    if ($(this).hasClass('header-red')) {
      $(this).removeClass('header-red');
    } else {
      $('.audit_card_header').removeClass('header-red');
      $(this).addClass('header-red');
    }

    if ($('#audit_ad').hasClass('header-red')) {
      $('.tab-pane').removeClass('show');
      $('#audit_ad_img').addClass('show');
    } else if ($('#audit_sales').hasClass('header-red')) {
      $('.tab-pane').removeClass('show');
      $('#audit_sales_img').addClass('show');
    } else if ($('#audit_site').hasClass('header-red')) {
      $('.tab-pane').removeClass('show');
      $('#audit_site_img').addClass('show');
    }
  });


  //E-mail Ajax Send
  //Форма заказа
  $("#order").submit(function() {
    var th = $(this);
    $.ajax({
      type: "POST",
      url: "https://tls.media/mail.php",
      data: th.serialize()
    }).done(function() {
      dataLayer.push({
        'event': 'formzayavka1'
      });
      //alert("Благодарим вас за обращение. Наш мереджер свяжется с вами в ближайшее время.");
      window.location.href = "https://tls.media/TLSmedia-thanx/"
      setTimeout(function() {
        // Done Functions
        th.trigger("reset");
      }, 1000);
    });
    return false;
  });


  //Скачать прайс
  $("#downloadPrice").submit(function() {
    var th = $(this);
    $.ajax({
      type: "POST",
      url: "https://tls.media/mail.php",
      data: th.serialize()
    }).done(function() {});

  });

  //заявка на аудит
  $("#auditForm").submit(function() {
    var th = $(this);
    $.ajax({
      type: "POST",
      url: "https://tls.media/mail.php",
      data: th.serialize()
    }).done(function() {
      dataLayer.push({
        'event': 'formzayavka2'
      });
      //alert("Благодарим вас за обращение. Наш мереджер свяжется с вами в ближайшее время.");
      window.location.href = "https://tls.media/TLSmedia-thanx/"
      setTimeout(function() {
        // Done Functions
        th.trigger("reset");
      }, 1000);
    });
    return false;
  });

  //FAQ
  $("#faqForm").submit(function() {
    var th = $(this);
    $.ajax({
      type: "POST",
      url: "https://tls.media/mail.php",
      data: th.serialize()
    }).done(function() {
      dataLayer.push({
        'event': 'formzayavka3'
      });
      //alert("Благодарим вас за обращение. Наш мереджер свяжется с вами в ближайшее время.");
      window.location.href = "https://tls.media/TLSmedia-thanx/"
      setTimeout(function() {
        // Done Functions
        th.trigger("reset");
      }, 1000);
    });
    return false;
  });


  $(function() {
    $('[data-toggle="popover"]').popover()
  })
});
